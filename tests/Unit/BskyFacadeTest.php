<?php

namespace Tests\Unit;

use Atproto\BskyFacade;
use Atproto\Client;
use Atproto\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\Reflection;

class BskyFacadeTest extends TestCase
{
    use Reflection;

    private BskyFacade $facade;
    private Client $client;

    public function setUp(): void
    {
        $this->client = new Client();
    }

    private function instance(): BskyFacade
    {
        return BskyFacade::getInstance($this->client);
    }

    public function testFacadeCanCreateInstance(): void
    {
        $facade = $this->instance();

        $this->assertInstanceOf(BskyFacade::class, $facade);
    }

    public function testFacadeThrowsExceptionWhenGettingInstanceWithoutClientSetForFirstTime(): void
    {
        $this->property('instance', $this->instance())->setValue(null);

        $this->expectException(InvalidArgumentException::class);

        BskyFacade::getInstance();
    }

    public function testFacadeIsSingleton(): void
    {
        $this->assertSame($this->instance(), $this->instance());
    }
}
