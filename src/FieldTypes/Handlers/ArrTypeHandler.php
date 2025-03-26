<?php

namespace Atproto\FieldTypes\Handlers;

use Atproto\Contracts\FieldTypes\FieldTypeHandlerContract;
use Atproto\FieldTypes\Definitions\AbstractDefinition;
use Atproto\FieldTypes\Definitions\ArrTypeDefinition;

class ArrTypeHandler implements FieldTypeHandlerContract
{
    /**
     * @param  mixed  $value
     * @param  ArrTypeDefinition  $arrDefinition
     * @return array
     */
    public function handle($value, AbstractDefinition $arrDefinition)
    {
        $arrDefinition = $this->definition($arrDefinition);

        return array_map(function($item) use ($arrDefinition) {
            return $arrDefinition->handler()->handle(
                $item,
                $arrDefinition->definition()
            );
        }, $value);
    }

    private function definition(ArrTypeDefinition $definition): ArrTypeDefinition
    {
        return $definition;
    }
}
