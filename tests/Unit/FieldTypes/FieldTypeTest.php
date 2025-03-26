<?php

namespace Unit\FieldTypes;

use Atproto\FieldTypes\CastableFields\CastableField;
use Atproto\FieldTypes\Definitions\ObjectTypeDefinition;
use Atproto\FieldTypes\Handlers\ObjectTypeHandler;
use PHPUnit\Framework\TestCase;

class FieldTypeTest extends TestCase
{
    public function test_handler_returns_expected_handler()
    {
        $definition = new ObjectTypeDefinition(FakeObject::class);
        $handler = new ObjectTypeHandler();
        $field = new CastableField($handler, $definition);

        $this->assertSame($handler, $field->handler());
    }

    public function test_definition_returns_expected_definition()
    {
        $definition = new ObjectTypeDefinition(FakeObject::class);
        $field = new CastableField(new ObjectTypeHandler(), $definition);

        $this->assertSame($definition, $field->definition());
    }

    public function test_magic_call_sets_metadata_correctly()
    {
        $definition = new ObjectTypeDefinition(FakeObject::class);
        $field = new CastableField(new ObjectTypeHandler(), $definition);

        $field->description('Test description');

        $this->assertEquals('Test description', $definition->description(null));
    }

    public function test_magic_call_throws_exception_for_invalid_method()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('ObjectTypeDefinition::');

        $definition = new ObjectTypeDefinition(FakeObject::class);
        $field = new CastableField(new ObjectTypeHandler(), $definition);

        $field->nonExistentMethod(); // should throw native exception
    }
}

class FakeObject
{
    // Dummy class for testing
}
