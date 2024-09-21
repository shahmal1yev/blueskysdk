<?php

namespace Tests\Unit;

use Atproto\Client;
use Atproto\Contracts\RequestContract;
use Atproto\Exceptions\Http\Request\RequestNotFoundException;
use Atproto\HTTP\API\APIRequest;
use Atproto\HTTP\API\Requests\Com\Atproto\Server\CreateSession;
use Atproto\Resources\Com\Atproto\Server\CreateSessionResource;
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

        $method = $this->method('request', $this->client);

        $namespace = $method->invoke($this->client);

        $expectedNamespace = 'Atproto\\HTTP\\API\\Requests\\App\\Bsky\\Actor';
        $this->assertSame($expectedNamespace, $namespace);
    }

    public function testForgeThrowsRequestNotFoundException(): void
    {
        $this->client->nonExistentMethod();

        $this->expectException(RequestNotFoundException::class);
        $this->expectExceptionMessage("Atproto\\HTTP\\API\\Requests\\NonExistentMethod class does not exist.");

        $this->client->forge();
    }

    /**
     * @throws RequestNotFoundException
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

    public function testAttachObserver(): void
    {
        $mockObserver = $this->createMock(SplObserver::class);
        $this->client->attach($mockObserver);

        $observers = $this->getPropertyValue('observers', $this->client);
        $this->assertCount(1, $observers);
        $this->assertTrue($observers->contains($mockObserver));
    }

    public function testDetachObserver(): void
    {
        $mockObserver = $this->createMock(SplObserver::class);
        $this->client->attach($mockObserver);
        $this->client->detach($mockObserver);

        $observers = $this->getPropertyValue('observers', $this->client);
        $this->assertCount(0, $observers);
    }

    public function testNotifyObservers(): void
    {
        $mockObserver1 = $this->createMock(SplObserver::class);
        $mockObserver2 = $this->createMock(SplObserver::class);

        $mockObserver1->expects($this->once())->method('update')->with($this->client);
        $mockObserver2->expects($this->once())->method('update')->with($this->client);

        $this->client->attach($mockObserver1);
        $this->client->attach($mockObserver2);

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

    public function testAPIRequestUpdatesOnNotify(): void
    {
        $this->mockAuthenticate();

        $mockRequest = $this->createMock(APIRequest::class);

        $this->client->attach($mockRequest);

        $mockRequest->expects($this->once())
            ->method('update')
            ->with($this->client);

        $this->client->authenticate('username', 'password');
    }

    private function mockAuthenticate()
    {
        $mockCreateSession = $this->getMockBuilder(CreateSession::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['send'])
            ->getMock();

        $mockCreateSession->expects($this->once())
            ->method('send')
            ->willReturn($this->createMock(CreateSessionResource::class));

        $this->client = $this->getMockBuilder(Client::class)
            ->onlyMethods(['forge'])
            ->getMock();

        $this->client->method('forge')
            ->willReturn($mockCreateSession);
    }
}
