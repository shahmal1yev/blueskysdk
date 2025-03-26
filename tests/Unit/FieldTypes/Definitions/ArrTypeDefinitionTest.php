<?php

namespace Tests\Unit\FieldTypes\Definitions;

use Atproto\Contracts\FieldTypes\FieldTypeHandlerContract;
use Atproto\FieldTypes\Definitions\AbstractDefinition;
use Atproto\FieldTypes\Definitions\ArrTypeDefinition;
use PHPUnit\Framework\TestCase;

class ArrTypeDefinitionTest extends TestCase
{
    public function test_type_is_array()
    {
        $def = new ArrTypeDefinition(
            $this->createMock(FieldTypeHandlerContract::class),
            $this->createMock(AbstractDefinition::class)
        );

        $this->assertEquals('array', $def->type());
    }

    public function test_handler_and_definition_accessors()
    {
        $handler = $this->createMock(FieldTypeHandlerContract::class);
        $innerDef = $this->createMock(AbstractDefinition::class);

        $def = new ArrTypeDefinition($handler, $innerDef);

        $this->assertSame($handler, $def->handler());
        $this->assertSame($innerDef, $def->definition());
    }

    public function test_items_set_and_get()
    {
        $def = $this->makeDefinition();
        $def->items('string');

        $this->assertEquals('string', $def->items());
    }

    public function test_min_length_set_and_get()
    {
        $def = $this->makeDefinition();
        $def->minLength(3);

        $this->assertEquals(3, $def->minLength());
    }

    public function test_max_length_set_and_get()
    {
        $def = $this->makeDefinition();
        $def->maxLength(10);

        $this->assertEquals(10, $def->maxLength());
    }

    public function test_description_metadata()
    {
        $def = $this->makeDefinition();
        $def->description('A list of items');

        $this->assertEquals('A list of items', $def->description(null));
    }

    private function makeDefinition(): ArrTypeDefinition
    {
        return new ArrTypeDefinition(
            $this->createMock(FieldTypeHandlerContract::class),
            $this->createMock(AbstractDefinition::class)
        );
    }
}
