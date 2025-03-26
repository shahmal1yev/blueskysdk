<?php

namespace Tests\Unit\FieldTypes\Factories;

use Atproto\FieldTypes\Definitions\ObjectTypeDefinition;
use Atproto\FieldTypes\Factories\ObjectTypePairFactory;
use Atproto\FieldTypes\Handlers\ObjectTypeHandler;
use PHPUnit\Framework\TestCase;

class ObjectTypePairFactoryTest extends TestCase
{
    public function test_it_returns_object_type_handler()
    {
        $handler = ObjectTypePairFactory::handler();

        $this->assertInstanceOf(ObjectTypeHandler::class, $handler);
    }

    public function test_it_returns_object_type_definition()
    {
        $targetClass = \stdClass::class;

        $definition = ObjectTypePairFactory::definition($targetClass);

        $this->assertInstanceOf(ObjectTypeDefinition::class, $definition);
        $this->assertEquals($targetClass, $definition->target());
    }
}
