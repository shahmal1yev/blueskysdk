<?php

namespace Tests\Unit\FieldTypes\CastableFields;

use Atproto\FieldTypes\CastableFields\CastableField;
use Atproto\FieldTypes\Definitions\AbstractDefinition;
use Atproto\Contracts\FieldTypes\FieldTypeHandlerContract;
use PHPUnit\Framework\TestCase;

class CastableFieldTest extends TestCase
{
    public function test_it_returns_handler_and_definition(): void
    {
        $definition = new DummyDefinition();
        $handler = new DummyHandler();

        $field = new CastableField($handler, $definition);

        $this->assertSame($handler, $field->handler());
        $this->assertSame($definition, $field->definition());
    }

    public function test_it_sets_metadata_via_call(): void
    {
        $definition = new DummyDefinition();
        $handler = new DummyHandler();
        $field = new CastableField($handler, $definition);

        $field->description('Test description');

        $this->assertEquals('Test description', $definition->metadata()['description']);
    }

    public function test_it_throws_exception_on_invalid_call(): void
    {
        $this->expectException(\Error::class);

        $definition = new DummyDefinition();
        $handler = new DummyHandler();
        $field = new CastableField($handler, $definition);

        $field->nonexistentMethod();
    }
}


class DummyDefinition extends AbstractDefinition
{
    public function description(?string $description)
    {
        parent::description($description);
        return $this;
    }
}

class DummyHandler implements FieldTypeHandlerContract
{
    public function handle($value, AbstractDefinition $definition)
    {
        return $value;
    }
}
