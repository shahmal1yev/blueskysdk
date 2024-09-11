<?php

namespace Tests\Unit\HTTP;

use ArgumentCountError;
use Atproto\HTTP\Request;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;
use TypeError;

class RequestTest extends TestCase
{
    protected Request $request;
    protected Generator $faker;

    protected function setUp(): void
    {
        $this->request = new Request();
        $this->faker = Factory::create();
    }

    /** @dataProvider methodProvider */
    public function testMethodSetsCorrectValue(string $method, string $property): void
    {
        $key = $this->faker->word;
        $expected = $this->faker->word;

        try {
            $reflectedProperty = $this->getPropertyValue($property);

            $value = (is_array($reflectedProperty))
                ? [$key => $expected]
                : $expected;

            $this->request->$method($value);
        } catch (TypeError $e) {
            $this->request->$method($key, $expected);
        }

        $actual = $this->getPropertyValue($property);

        if (is_array($actual)) {
            $this->assertArrayHasKey($key, $actual);
            $actual = $actual[$key];
        }

        $this->assertSame($expected, $actual);
    }

    /** @dataProvider methodProvider */
    public function testMethodReturnsCorrectValue(string $method, string $property): void
    {
        $key = $this->faker->word;
        $expected = $this->faker->word;

        $propertyValue = $this->getPropertyValue($property);

        if (is_array($propertyValue)) {
            $expected = [$key => $expected];
        }

        $this->setPropertyValue($property, $expected);

        try {
            $actual = $this->request->$method();
        } catch (ArgumentCountError $e) {
            $actual = $this->request->$method($key);
            $expected = current($expected);
        }

        $this->assertSame($expected, $actual);
    }

    /** @dataProvider methodProvider */
    public function testMethodReturnsSameInstanceWhenSettingValue(string $method, string $property): void
    {
        $propertyValue = $this->getPropertyValue($property);

        $value = is_array($propertyValue)
            ? [$this->faker->word]
            : $this->faker->word;

        try {
            $actual = $this->request->$method($value);
        } catch (TypeError $e) {
            $actual = $this->request->$method($this->faker->word, $this->faker->word);
        }

        $this->assertInstanceOf(Request::class, $actual);
        $this->assertSame($this->request, $actual);
    }

    /** @dataProvider encodableProvider */
    public function testMethodsReturnEncodableContentCorrectly(string $property, string $method, string $verifier): void
    {
        $content = $this->randomArray();

        $this->setPropertyValue($property, $content);

        $expected = $content;
        $actual = $this->request->$method(true);

        $this->$verifier($expected, $actual);
    }

    public function encodableProvider(): array
    {
        return [
            ['headers', 'headers', 'assertHeadersEncodedCorrectly'],
            ['parameters', 'parameters', 'assertParametersEncodedCorrectly'],
            ['queryParameters', 'queryParameters', 'assertQueryParametersEncodedCorrectly'],
        ];
    }

    public function methodProvider(): array
    {
        # [method, property]

        return [
            ['url', 'url'],
            ['path', 'path'],
            ['method', 'method'],
            ['header', 'headers'],
            ['headers', 'headers'],
            ['parameter', 'parameters'],
            ['parameters', 'parameters'],
            ['queryParameter', 'queryParameters'],
            ['queryParameters', 'queryParameters'],
        ];
    }

    protected function assertHeadersEncodedCorrectly(array $expected, array $actual): void
    {
        $expected = array_map(fn ($key, $value) => "$key: $value", array_keys($expected), array_values($expected));

        $this->assertEquals($expected, $actual);
        $this->assertIsArray($actual);
    }

    protected function assertParametersEncodedCorrectly(array $expected, string $actual): void
    {
        $expected = json_encode($expected);

        $this->assertSame($expected, $actual);
        $this->assertIsString($actual);
    }

    protected function assertQueryParametersEncodedCorrectly(array $expected, string $actual): void
    {
        $expected = http_build_query($expected);

        $this->assertSame($expected, $actual);
        $this->assertIsString($actual);
    }

    protected function randomArray(int $count = null): array
    {
        $count = $count ?: $this->faker->numberBetween(1, 100);

        $keys = [];
        $values = [];

        for ($i = 0; $i < $count; $i++) {
            $keys[] = $this->faker->word;
            $values[] = $this->faker->word;
        }

        return array_combine(
            $keys,
            $values
        );
    }

    protected function property(string $name): ReflectionProperty
    {
        $reflection = new ReflectionClass($this->request);
        $property = $reflection->getProperty($name);
        $property->setAccessible(true);

        return $property;
    }

    protected function getPropertyValue(string $propertyName)
    {
        return $this->property($propertyName)->getValue($this->request);
    }

    protected function setPropertyValue(string $propertyName, $value): void
    {
        $this->property($propertyName)->setValue($this->request, $value);
    }
}
