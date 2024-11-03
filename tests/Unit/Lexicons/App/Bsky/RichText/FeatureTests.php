<?php

namespace Tests\Unit\Lexicons\App\Bsky\RichText;

use Atproto\Lexicons\App\Bsky\RichText\FeatureAbstract;
use ReflectionException;
use Tests\Supports\Reflection;

trait FeatureTests
{
    use Reflection;

    private string $label;
    private string $reference;

    public function setUp(): void
    {
        $this->label = 'label';
        $this->reference = 'reference';
    }

    public function test__toStringReturnsCorrectLabelWhenPassedBothParameters(): void
    {
        $feature = $this->feature($this->reference, $this->label);
        $this->assert__toString($this->label, $feature);
    }

    public function test__toStringReturnsCorrectLabelWhenPassedSingleParameter(): void
    {
        $feature = $this->feature($this->reference);

        $this->assert__toString($this->reference, $feature);
    }

    private function assert__toString(string $expected, FeatureAbstract $feature): void
    {
        $this->assertEquals(
            $this->prefix . $expected,
            $feature
        );
    }

    public function testSchemaReturnsCorrectSchemaWhenPassedBothParameters(): void
    {
        $feature = $this->feature($this->reference, $this->label);

        $expected = [
            'label' => $this->prefix . $this->label,
            $this->key => $this->reference,
        ];

        $this->assertSchema($expected, $feature);
    }

    public function testSchemaReturnsCorrectSchemaWhenPassedSingleParameter(): void
    {
        $expected = $this->schema($this->reference);

        $this->assertSchema($expected, $this->feature($this->reference));
    }

    private function schema(string $label): array
    {
        return [
            'label' => $this->prefix . $label,
            $this->key => $this->reference,
        ];
    }

    private function assertSchema(array $expected, FeatureAbstract $feature): void
    {
        $this->assertSame(
            $expected,
            json_decode(json_encode($feature), true)
        );

        $this->assertSame(
            $expected,
            $feature->jsonSerialize()
        );
    }

    private function feature(string $reference, string $label = null): FeatureAbstract
    {
        $namespace = $this->namespace;

        if (! is_null($label)) {
            return new $namespace($reference, $label);
        }

        return new $namespace($reference);
    }
}
