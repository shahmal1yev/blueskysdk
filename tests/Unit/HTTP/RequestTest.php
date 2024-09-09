<?php

namespace Tests\Unit\HTTP;

use Atproto\Contracts\HTTP\RequestContract;
use Atproto\HTTP\Request;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class RequestTest extends TestCase
{
    protected Request $request;
    protected Generator $faker;

    protected function setUp(): void
    {
        $this->request = new Request();
        $this->faker = Factory::create();
    }

    public function testHeaderReturnsCorrectValue(): void
    {
        [$name, $value] = $this->setHeader();

        $this->assertSame($value, $this->request->header($name));
    }

    public function testHeaderSetsCorrectValue(): void
    {
        [$name, $value] = $this->randomPair();

        $this->request->header($name, $value);

        $headers = $this->getPropertyValue('headers');

        $this->assertArrayHasKey($name, $headers);
        $this->assertSame($value, $headers[$name]);
    }

    public function testHeaderReturnsSameInstanceAfterSettingNewValue(): void
    {
        [$name, $value] = $this->setHeader();

        $result = $this->request->header($name, $value);

        $this->assertInstanceOf(Request::class, $result);
        $this->assertInstanceOf(RequestContract::class, $result);
    }

    /**
     * @throws ReflectionException
     */
    public function testHeadersReturnCorrectEncodedArray(): void
    {
        $headerCount = $this->faker->numberBetween(1, 30);
        $headers = [];

        for ($i = 0; $i < $headerCount; $i++) {
            [$name, $value] = $this->randomPair();
            $headers[$name] = $value;
        }

        $this->setPropertyValue('headers', $headers);

        $encodedHeaders = $this->request->headers(true);

        foreach ($headers as $name => $value) {
            $this->assertContains("$name: $value", $encodedHeaders);
        }
    }

    /**
     * @throws ReflectionException
     */
    public function testHeadersSetCorrectValues(): void
    {
        $headers = [];

        for ($i = 0, $count = $this->faker->numberBetween(1, 30); $i < $count; $i++) {
            [$name, $value] = $this->randomPair();
            $headers[$name] = $value;
        }

        $this->request->headers($headers);

        $this->assertSame($headers, $this->getPropertyValue('headers'));
    }

    public function testHeadersReturnSameInstanceAfterSettingNewValues(): void
    {
        [$name, $value] = $this->randomPair();
        $headers = [$name => $value];

        $result = $this->request->headers($headers);

        $this->assertInstanceOf(Request::class, $result);
        $this->assertInstanceOf(RequestContract::class, $result);
    }

    protected function randomPair(): array
    {
        return [
            $this->faker->shuffleString(),
            $this->faker->shuffleString(),
        ];
    }

    protected function setHeader(): array
    {
        [$name, $value] = $this->randomPair();
        $this->request->header($name, $value);
        return [$name, $value];
    }

    protected function property($name): ReflectionProperty
    {
        $reflection = new ReflectionClass($this->request);
        $property = $reflection->getProperty($name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * @throws ReflectionException
     */
    protected function getPropertyValue(string $propertyName)
    {
        $property = $this->property($propertyName);
        return $property->getValue($this->request);
    }

    /**
     * @throws ReflectionException
     */
    protected function setPropertyValue(string $propertyName, $value): void
    {
        $property = $this->property($propertyName);
        $property->setValue($this->request, $value);
    }
}
