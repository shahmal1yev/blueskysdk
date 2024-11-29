<?php

namespace Tests\Unit;

use Atproto\Client;
use Atproto\Contracts\HTTP\AuthEndpointLexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\Http\Request\LexiconNotFoundException;
use Atproto\Exceptions\Http\Response\AuthenticationRequiredException;
use Atproto\Factories\PSR\PSR17Factory;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\App\Bsky\Actor\GetProfile;
use Atproto\Lexicons\Com\Atproto\Server\CreateSession;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\App\Bsky\Actor\GetProfileResponse;
use Atproto\Responses\Com\Atproto\Server\CreateSessionResponse;
use Nyholm\Psr7\Stream;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use SplObserver;
use Tests\Supports\Reflection;

class ClientTest extends TestCase
{
    use Reflection;

    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client();
    }

    /**
     * @throws ReflectionException
     */
    public function testDynamicMethodCalls(): void
    {
        $this->client->app()->bsky()->actor();

        $path = $this->getPropertyValue('path', $this->client);

        $this->assertSame(['app', 'bsky', 'actor'], $path);
    }

    /**
     * @throws ReflectionException
     */
    public function testNamespaceGeneration(): void
    {
        $this->client->app()->bsky()->actor();

        $method = $this->method('namespace', $this->client);

        $namespace = implode('\\', array_map(
            'ucfirst',
            $this->getPropertyValue('path', $this->client))
        );

        $expectedNamespace = 'App\\Bsky\\Actor';
        $this->assertSame($expectedNamespace, $namespace);
    }

    public function testForgeThrowsRequestNotFoundException(): void
    {
        $this->client->nonExistentMethod();

        $this->expectException(LexiconNotFoundException::class);
        $this->expectExceptionMessage("Atproto\\Lexicons\\NonExistentMethod lexicon does not exist.");

        $this->client->forge();
    }

    /**
     * @throws LexiconNotFoundException
     */
    public function testForgeReturnsRequestContract(): void
    {
        $this->client->app()->bsky()->actor()->getProfile();

        $request = $this->client->forge();

        $this->assertInstanceOf(RequestContract::class, $request);
    }

    /**
     * @throws ReflectionException
     */
    public function testRefreshClearsPath(): void
    {
        $this->client->app()->bsky()->actor();

        $method = $this->method('refresh', $this->client);

        $method->invoke($this->client);

        $path = $this->getPropertyValue('path', $this->client);

        $this->assertEmpty($path);
    }

    public function testAuthenticatedReturnsNullWhenNotAuthenticated(): void
    {
        $this->assertNull($this->client->authenticated());
    }

    private function authenticatedEndpointTraitMock(): RequestContract
    {
        return new class () implements RequestContract {
            use AuthenticatedEndpoint;
            protected function response(ResponseContract $response): ResponseContract {}
        };
    }

    public function testAttachObserver(): void
    {
        $mockObserver = $this->createMock(AuthEndpointLexiconContract::class);
        $this->client->attach($mockObserver);

        $observers = $this->getPropertyValue('observers', $this->client);
        $this->assertCount(1, $observers);
        $this->assertTrue($observers->contains($mockObserver));
    }

    public function testDetachObserver(): void
    {
        $mockObserver = $this->createMock(AuthEndpointLexiconContract::class);
        $this->client->attach($mockObserver);
        $this->client->detach($mockObserver);

        $observers = $this->getPropertyValue('observers', $this->client);
        $this->assertCount(0, $observers);
    }

    public function testNotifyObservers(): void
    {
        $this->setPropertyValue(
            'authenticated',
            $authenticated = new CreateSessionResponse($this->createMock(ResponseContract::class)),
            $this->client,
        );

        for($i=0; 5 > $i; $i++) {
            $observer = $this->createMock(AuthEndpointLexiconContract::class);
            $observer->expects($this->once())->method('update')->with($authenticated);
            $this->client->attach($observer);
        }

        $this->client->notify();
    }

    public function testForgeAttachesObserver(): void
    {
        $this->client->app()->bsky()->actor()->getProfile();
        $request = $this->client->forge();

        $observers = $this->getPropertyValue('observers', $this->client);
        $this->assertCount(1, $observers);
        $this->assertTrue($observers->contains($request));
    }

    private function mockAuthenticate()
    {
        $mockCreateSession = $this->getMockBuilder(CreateSession::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['send'])
            ->getMock();

        $mockCreateSession->expects($this->once())
            ->method('send')
            ->willReturn($this->createMock(CreateSessionResponse::class));

        $this->client = $this->getMockBuilder(Client::class)
            ->onlyMethods(['forge'])
            ->getMock();

        $this->client->method('forge')
            ->willReturn($mockCreateSession);
    }

    public function testItCanReturnsGetProfile(): void
    {
        $getProfile = $this->client->app()->bsky()->actor()->getProfile()->forge();

        $credentials = [
            'shahmal1yevv.bsky.social',
            'ucvlqcq8'
        ];

        $this->client->authenticate(
            ...$credentials
        );

        $headers = $getProfile->getHeaders();
        $this->assertArrayHasKey('Authorization', $headers);

        $this->assertInstanceOf(GetProfile::class, $getProfile);
        $this->assertNotSame($this->client->authenticated()->did(), $getProfile->actor());

        $getProfile = $getProfile->actor($this->client->authenticated()->did());

        $this->assertInstanceOf(GetProfile::class, $getProfile);
        $this->assertSame($this->client->authenticated()->did(), $getProfile->actor());

        $response = $getProfile->send();

        $this->assertInstanceOf(GetProfileResponse::class, $response);
        $this->assertSame($this->client->authenticated()->did(), $response->did());

        $clone = $response->withProtocolVersion('2');

        $this->assertSame($this->client->authenticated()->did(), $clone->did());
        $this->assertEquals($response, $clone);
    }
}
