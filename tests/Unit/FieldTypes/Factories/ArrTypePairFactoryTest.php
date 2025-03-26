<?php

namespace Tests\Unit\FieldTypes\Factories;

use Atproto\Contracts\FieldTypes\FieldTypeHandlerContract;
use Atproto\FieldTypes\Definitions\AbstractDefinition;
use Atproto\FieldTypes\Definitions\ArrTypeDefinition;
use Atproto\FieldTypes\Factories\ArrTypePairFactory;
use Atproto\FieldTypes\Handlers\ArrTypeHandler;
use PHPUnit\Framework\TestCase;

class ArrTypePairFactoryTest extends TestCase
{
    public function test_it_returns_arr_type_handler()
    {
        $handler = ArrTypePairFactory::handler();

        $this->assertInstanceOf(ArrTypeHandler::class, $handler);
    }

    public function test_it_returns_arr_type_definition()
    {
        $innerHandler = $this->createMock(FieldTypeHandlerContract::class);
        $innerDefinition = $this->createMock(AbstractDefinition::class);

        $definition = ArrTypePairFactory::definition($innerHandler, $innerDefinition);

        $this->assertInstanceOf(ArrTypeDefinition::class, $definition);
        $this->assertSame($innerHandler, $definition->handler());
        $this->assertSame($innerDefinition, $definition->definition());
    }
}
