<?php

namespace Tests\Unit\Lexicons\App\Bsky\Actor;

use Atproto\Client;
use Atproto\Contracts\Lexicons\APIRequestContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\App\Bsky\Actor\GetProfile;
use Atproto\Lexicons\Request;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Tests\Supports\Reflection;

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
        $request = $this->request->actor($expected);
        $actual = $request->queryParameter('actor');

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
        $request = $this->request->queryParameter('actor', $expected);

        // Act
        $actual = $request->actor();

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
    }

    public function testBuildReturnsSameInterface(): void
    {
        $actual = $this->request->token($this->faker->word)
            ->actor($this->faker->word);

        $this->assertInstanceOf(RequestContract::class, $actual, 'Should return an instance of RequestContract.');
    }
}
