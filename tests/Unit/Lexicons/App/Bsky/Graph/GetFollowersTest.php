<?php

namespace Tests\Unit\Lexicons\App\Bsky\Graph;

use Atproto\Client;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Exceptions\Http\Response\AuthMissingException;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Graph\GetFollowers;
use PHPUnit\Framework\TestCase;

class GetFollowersTest extends TestCase
{
    /** @var GetFollowers */
    private $request;


    protected function setUp(): void
    {
        $this->request = new GetFollowers();
    }

    public function testActorSetterAndGetter()
    {
        $actor = 'testActor';
        $request = $this->request->actor($actor);
        $this->assertSame($actor, $request->actor(), 'Actor getter should return the value set by the setter.');
    }

    public function testLimitSetterAndGetter()
    {
        $limit = 50;
        $request = $this->request->limit($limit);
        $this->assertSame($limit, $request->limit(), 'Limit getter should return the value set by the setter.');
    }

    public function testLimitSetterThrowsExceptionForZero()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit must be between 1 and 100.');
        $this->request->limit(0);
    }

    public function testLimitSetterThrowsExceptionForNegativeValue()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit must be between 1 and 100.');
        $this->request->limit(-10);
    }

    public function testLimitSetterThrowsExceptionForValueAboveMaximum()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit must be between 1 and 100.');
        $this->request->limit(101);
    }

    public function testCursorSetterAndGetter()
    {
        $cursor = 'testCursor';
        $request = $this->request->cursor($cursor);
        $this->assertSame($cursor, $request->cursor(), 'Cursor getter should return the value set by the setter.');
    }

    public function testResourceMethodReturnsCorrectInstance()
    {
        $resource = $this->request->response($this->createMock(ResponseContract::class));
        $this->assertInstanceOf(ResponseContract::class, $resource, 'Resource method should return an instance of ResourceContract.');
    }

    public function testLimitGetterReturnsNullWhenNotSet()
    {
        $this->assertNull($this->request->limit(), 'Limit getter should return null when not set.');
    }

    public function testActorGetterReturnsNullWhenNotSet()
    {
        $this->assertNull($this->request->actor(), 'Actor getter should return null when not set.');
    }

    public function testCursorGetterReturnsNullWhenNotSet()
    {
        $this->assertNull($this->request->cursor(), 'Cursor getter should return null when not set.');
    }
}
