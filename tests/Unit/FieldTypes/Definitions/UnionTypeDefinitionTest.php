<?php

namespace Tests\Unit\FieldTypes\Definitions;

use Atproto\FieldTypes\Definitions\UnionTypeDefinition;
use Atproto\Contracts\LexiconContract;
use PHPUnit\Framework\TestCase;

class UnionTypeDefinitionTest extends TestCase
{
    public function test_type_is_union()
    {
        $def = new UnionTypeDefinition([
            DummyLexicon1::class,
            DummyLexicon2::class,
        ]);

        $this->assertEquals('union', $def->type());
    }

    public function test_refs_returns_given_classes()
    {
        $refs = [DummyLexicon1::class, DummyLexicon2::class];
        $def = new UnionTypeDefinition($refs);

        $this->assertEquals($refs, $def->refs());
    }

    public function test_closed_set_and_get()
    {
        $def = new UnionTypeDefinition([DummyLexicon1::class]);

        $def->closed(true);
        $this->assertTrue($def->closed());

        $def->closed(false);
        $this->assertFalse($def->closed());
    }

    public function test_description_works()
    {
        $def = new UnionTypeDefinition([DummyLexicon1::class]);
        $def->description('This is a union');

        $this->assertEquals('This is a union', $def->description(null));
    }
}

class DummyLexicon1 implements LexiconContract
{
    public static function nsid(): string
    {
        return 'com.example.dummy1';
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

class DummyLexicon2 implements LexiconContract
{
    public static function nsid(): string
    {
        return 'com.example.dummy2';
    }

    public function jsonSerialize(): array
    {
        return [];
    }

    public function __toString(): string
    {
        return 'bar';
    }
}
