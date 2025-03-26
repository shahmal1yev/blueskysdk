<?php

namespace Tests\Unit\FieldTypes\Handlers;

use Atproto\Contracts\LexiconContract;
use Atproto\FieldTypes\Definitions\UnionTypeDefinition;
use Atproto\FieldTypes\Handlers\UnionTypeHandler;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UnionTypeHandlerTest extends TestCase
{
    public function test_it_resolves_to_correct_variant()
    {
        $data = ['$type' => FakeLexicon::nsid(), 'payload' => 'ok'];

        $definition = new UnionTypeDefinition([FakeLexicon::class]);
        $handler = new UnionTypeHandler();

        $result = $handler->handle($data, $definition);

        $this->assertInstanceOf(FakeLexicon::class, $result);
        $this->assertEquals($data, $result->getData());
    }

    public function test_it_throws_when_type_does_not_match_any_ref()
    {
        $this->expectException(InvalidArgumentException::class);

        $data = ['$type' => 'unknown.nsid#type'];

        $definition = new UnionTypeDefinition([FakeLexicon::class]);
        $handler = new UnionTypeHandler();

        $handler->handle($data, $definition);
    }

    public function test_it_throws_when_refs_include_invalid_classes()
    {
        $this->expectException(InvalidArgumentException::class);

        new UnionTypeDefinition([\stdClass::class]);
    }
}

class FakeLexicon implements LexiconContract
{
    private array $data;
    public function __construct(array $data) { $this->data = $data; }
    public function getData(): array { return $this->data; }
    public static function nsid(): string { return 'com.example.fake#view'; }
    public function jsonSerialize(): array {return [];}

    public function __toString(): string
    {
        return 'foo';
    }
}
