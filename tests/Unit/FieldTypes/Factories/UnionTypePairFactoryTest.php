<?php

namespace Tests\Unit\FieldTypes\Factories;

use Atproto\Contracts\LexiconContract;
use Atproto\FieldTypes\Definitions\UnionTypeDefinition;
use Atproto\FieldTypes\Factories\UnionTypePairFactory;
use Atproto\FieldTypes\Handlers\UnionTypeHandler;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UnionTypePairFactoryTest extends TestCase
{
    public function test_it_returns_union_type_handler()
    {
        $handler = UnionTypePairFactory::handler();

        $this->assertInstanceOf(UnionTypeHandler::class, $handler);
    }

    public function test_it_returns_union_type_definition()
    {
        $definition = UnionTypePairFactory::definition([
            DummyLexiconClass::class,
        ]);

        $this->assertInstanceOf(UnionTypeDefinition::class, $definition);
        $this->assertContains(DummyLexiconClass::class, $definition->refs());
    }

    public function test_it_throws_exception_for_invalid_refs()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/invalid/i');

        UnionTypePairFactory::definition([
            \stdClass::class,
        ]);
    }
}

class DummyLexiconClass implements LexiconContract
{
    public static function nsid(): string
    {
        return 'com.example.dummy#def';
    }

    public function jsonSerialize(): array
    {
        return [];
    }

    public function __toString(): string
    {
        return 'foo';
    }
}
