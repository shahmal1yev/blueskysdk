<?php

namespace Tests\Unit\FieldTypes\Definitions;

use Atproto\FieldTypes\CastableFields\CastableField;
use Atproto\FieldTypes\Definitions\ObjectTypeDefinition;
use PHPUnit\Framework\TestCase;

class ObjectTypeDefinitionTest extends TestCase
{
    public function test_type_is_object()
    {
        $def = new ObjectTypeDefinition(\stdClass::class);
        $this->assertEquals('object', $def->type());
    }

    public function test_target_returns_correct_class()
    {
        $def = new ObjectTypeDefinition(\stdClass::class);
        $this->assertEquals(\stdClass::class, $def->target());
    }

    public function test_nullable_set_and_get()
    {
        $def = new ObjectTypeDefinition(\stdClass::class);

        $def->nullable(true);
        $this->assertTrue($def->nullable());

        $def->nullable(false);
        $this->assertFalse($def->nullable());
    }

    public function test_properties_are_stored_and_retrieved()
    {
        $field = $this->createMock(CastableField::class);

        $def = new ObjectTypeDefinition(\stdClass::class);
        $def->properties(['foo' => $field]);

        $this->assertArrayHasKey('foo', $def->properties());
        $this->assertSame($field, $def->properties()['foo']);
    }

    public function test_required_fields()
    {
        $def = new ObjectTypeDefinition(\stdClass::class);
        $def->required(['foo', 'bar']);

        $this->assertEquals(['foo', 'bar'], $def->required());
    }

    public function test_description_set_and_get()
    {
        $def = new ObjectTypeDefinition(\stdClass::class);
        $def->description('This is a test');

        $this->assertEquals('This is a test', $def->description(null));
    }
}
