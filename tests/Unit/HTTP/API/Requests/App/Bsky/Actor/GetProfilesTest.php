<?php

namespace Tests\Unit\HTTP\API\Requests\App\Bsky\Actor;

use Atproto\Client;
use Atproto\Exceptions\Auth\AuthRequired;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\HTTP\API\Requests\App\Bsky\Actor\GetProfiles;
use Atproto\Resources\App\Bsky\Actor\GetProfilesResource;
use GenericCollection\GenericCollection;
use GenericCollection\Types\Primitive\StringType;
use PHPUnit\Framework\TestCase;

class GetProfilesTest extends TestCase
{
    private GetProfiles $request;
    private Client $client;

    public function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->request = new GetProfiles($this->client);
    }

    /**
     * @throws InvalidArgumentException
     * @throws \GenericCollection\Exceptions\InvalidArgumentException
     */
    public function testActorsSetterAndGetter(): void
    {
        $actors = new GenericCollection(new StringType(), ['actor1', 'actor2']);
        $this->request->actors($actors);
        $this->assertSame($actors, $this->request->actors(), 'Actors getter should return the value set by the setter.');
    }

    public function testActorsSetterThrowsExceptionForInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("'\$actors' collection must be of type 'GenericCollection\Types\Primitive\StringType'");
        $this->request->actors(new GenericCollection(\stdClass::class));
    }

    public function testActorsSetterThrowsExceptionForTooFewActors(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("'\$actors' collection count must be between 1 and 25");
        $this->request->actors(new GenericCollection(StringType::class));
    }

    /**
     * @throws \GenericCollection\Exceptions\InvalidArgumentException
     */
    public function testActorsSetterThrowsExceptionForTooManyActors(): void
    {
        $actors = new GenericCollection(new StringType());
        for ($i = 0; $i <= 25; $i++) {
            $actors->add($i, "actor$i");
        }

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("'\$actors' collection count must be between 1 and 25");
        $this->request->actors($actors);
    }

    public function testBuildThrowsAuthRequiredException(): void
    {
        $this->expectException(AuthRequired::class);
        $this->request->build();
    }

    public function testBuildThrowsMissingFieldProvidedException(): void
    {
        $this->request->token('Bearer token');
        $this->expectException(MissingFieldProvidedException::class);
        $this->expectExceptionMessage('actors');
        $this->request->build();
    }

    public function testBuildSucceeds(): void
    {
        $actors = new GenericCollection(new StringType(), ['actor1']);
        $this->request->token('Bearer token')->actors($actors);

        $this->assertInstanceOf(GetProfiles::class, $this->request->build(), 'Build should return an instance of GetProfiles.');
    }

    public function testResourceMethodReturnsCorrectInstance(): void
    {
        $data = ['actors' => []];
        $resource = $this->request->resource($data);
        $this->assertInstanceOf(GetProfilesResource::class, $resource, 'Resource method should return an instance of GetProfilesResource.');
    }
}
