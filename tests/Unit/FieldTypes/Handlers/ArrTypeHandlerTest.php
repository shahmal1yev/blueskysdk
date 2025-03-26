<?php

namespace Tests\Unit\FieldTypes\Handlers;

use Atproto\Contracts\FieldTypes\FieldTypeHandlerContract;
use Atproto\FieldTypes\Definitions\AbstractDefinition;
use Atproto\FieldTypes\Definitions\ArrTypeDefinition;
use Atproto\FieldTypes\Handlers\ArrTypeHandler;
use PHPUnit\Framework\TestCase;

class ArrTypeHandlerTest extends TestCase
{
    public function test_array_handler_casts_each_item()
    {
        $input = ['one', 'two', 'three'];
        $expected = ['ONE', 'TWO', 'THREE'];

        $mockInnerHandler = new class implements FieldTypeHandlerContract {
            public function handle($value, AbstractDefinition $definition)
            {
                return strtoupper($value);
            }
        };

        $mockInnerDef = $this->createMock(AbstractDefinition::class);

        $arrDef = new ArrTypeDefinition($mockInnerHandler, $mockInnerDef);
        $handler = new ArrTypeHandler();

        $result = $handler->handle($input, $arrDef);

        $this->assertEquals($expected, $result);
    }
}
