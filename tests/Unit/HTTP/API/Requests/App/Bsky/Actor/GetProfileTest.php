<?php

namespace Tests\Unit\HTTP\API\Requests\App\Bsky\Actor;

use Atproto\Contracts\HTTP\APIRequestContract;
use Atproto\Contracts\RequestContract;
use Atproto\HTTP\API\APIRequest;
use Atproto\HTTP\API\Requests\App\Bsky\Actor\GetProfile;
use Atproto\HTTP\Request;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Tests\Supports\Reflection;
use Atproto\Exceptions\Http\MissingProvidedFieldException;

class GetProfileTest extends TestCase
{
    use Reflection;

    protected Generator $faker;
    protected GetProfile $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->request = new GetProfile();
    }

    /**
     * @throws ReflectionException
     */
    public function testActorMethodSetsCorrectData(): void
    {
        // Arrange
        $expected = $this->faker->word;

        // Act
        $this->request->actor($expected);
        $actual = $this->getPropertyValue('actor', $this->request);

        // Assert
        $this->assertSame($expected, $actual, 'Actor should be set correctly.');
        $this->assertIsString($actual, 'Actor should be a string.');
    }

    /**
     * @throws ReflectionException
     */
    public function testActorMethodGetsCorrectData(): void
    {
        // Arrange
        $expected = $this->faker->word;
        $this->setPropertyValue('actor', $expected, $this->request);

        // Act
        $actual = $this->request->actor();

        // Assert
        $this->assertSame($expected, $actual, 'Actor should be retrieved correctly.');
        $this->assertIsString($actual, 'Actor should be a string.');
    }

    public function testActorMethodReturnsRequestInstance(): void
    {
        // Arrange & Act
        $actual = $this->request->actor($this->faker->word);

        // Assert
        $this->assertInstanceOf(RequestContract::class, $actual, 'Should return an instance of RequestContract.');
        $this->assertInstanceOf(APIRequestContract::class, $actual, 'Should return an instance of APIRequestContract.');
        $this->assertInstanceOf(Request::class, $actual, 'Should return an instance of Request.');
        $this->assertInstanceOf(APIRequest::class, $actual, 'Should return an instance of APIRequest.');
    }

    public function testBuildReturnsSameInterface(): void
    {
        $this->request->actor($this->faker->word);
        $this->request->token($this->faker->word);

        $actual = $this->request->build();

        $this->assertInstanceOf(RequestContract::class, $actual, 'Should return an instance of RequestContract.');
        $this->assertInstanceOf(APIRequestContract::class, $actual, 'Should return an instance of APIRequestContract.');
        $this->assertInstanceOf(Request::class, $actual, 'Should return an instance of Request.');
        $this->assertInstanceOf(APIRequest::class, $actual, 'Should return an instance of APIRequest.');
    }

    public function testBuildThrowsAnExceptionWhenActorDoesNotExist(): void
    {
        $this->expectException(MissingProvidedFieldException::class);
        $this->expectExceptionMessage("Missing provided fields: actor, token");

        $this->request->build();
    }
}
