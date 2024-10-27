<?php

namespace Tests\Unit\HTTP\API\Requests\App\Bsky\Graph;

use Atproto\Client;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Exceptions\Http\Response\AuthMissingException;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Graph\GetFollowers;
use PHPUnit\Framework\TestCase;

class GetFollowersTest extends TestCase
{
    /** @var GetFollowers */
    private $request;

    private Client $clientMock;

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(Client::class);
        $this->request = new GetFollowers($this->clientMock);
    }

    public function testActorSetterAndGetter()
    {
        $actor = 'testActor';
        $this->request->actor($actor);
        $this->assertSame($actor, $this->request->actor(), 'Actor getter should return the value set by the setter.');
    }

    public function testLimitSetterAndGetter()
    {
        $limit = 50;
        $this->request->limit($limit);
        $this->assertSame($limit, $this->request->limit(), 'Limit getter should return the value set by the setter.');
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
        $this->request->cursor($cursor);
        $this->assertSame($cursor, $this->request->cursor(), 'Cursor getter should return the value set by the setter.');
    }

    public function testBuildThrowsExceptionWhenActorParameterMissing()
    {
        $this->expectException(MissingFieldProvidedException::class);
        $this->expectExceptionMessage("Missing fields provided: actor");

        // Do not set 'actor' parameter
        $this->request->build();
    }

    /**
     * @throws MissingFieldProvidedException
     * @throws AuthMissingException
     */
    public function testBuildSucceedsWithRequiredParameters()
    {
        // Set required 'Authorization' header and 'actor' parameter
        $this->request->actor('testActor');

        // Should not throw any exceptions
        $builtRequest = $this->request->build();
        $this->assertInstanceOf(GetFollowers::class, $builtRequest, 'Build should return an instance of GetFollowers.');
    }

    public function testResourceMethodReturnsCorrectInstance()
    {
        $data = ['followers' => []];
        $resource = $this->request->resource($data);
        $this->assertInstanceOf(ResourceContract::class, $resource, 'Resource method should return an instance of ResourceContract.');
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
