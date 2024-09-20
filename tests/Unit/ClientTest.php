<?php

namespace Tests\Unit;

use Atproto\Client;
use Atproto\Contracts\RequestContract;
use Atproto\Exceptions\Http\Request\RequestNotFoundException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
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

    public function testBuildThrowsRequestNotFoundException(): void
    {
        $this->client->nonExistentMethod();

        $this->expectException(RequestNotFoundException::class);
        $this->expectExceptionMessage("Atproto\\HTTP\\API\\Requests\\NonExistentMethod class does not exist.");

        $this->client->forge();
    }

    /**
     * @throws RequestNotFoundException
     */
    public function testBuildReturnsRequestContract(): void
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
}
