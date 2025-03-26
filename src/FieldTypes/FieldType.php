<?php

namespace Atproto\FieldTypes;

use Atproto\Contracts\LexiconContract;
use Atproto\FieldTypes\CastableFields\CastableArrayField;
use Atproto\FieldTypes\CastableFields\CastableField;
use Atproto\FieldTypes\CastableFields\CastableObjectField;
use Atproto\FieldTypes\CastableFields\CastableUnionField;
use Atproto\FieldTypes\Factories\ArrTypePairFactory;
use Atproto\FieldTypes\Factories\ObjectTypePairFactory;
use Atproto\FieldTypes\Factories\UnionTypePairFactory;

class FieldType
{
    /**
     * @param  CastableField  $inner
     * @return CastableArrayField
     */
    public static function array(CastableField $inner): CastableArrayField
    {
        $pairFactory = new ArrTypePairFactory();

        return new CastableArrayField(
            $pairFactory->handler(),
            $pairFactory->definition($inner->handler(), $inner->definition())
        );
    }

    /**
     * @param  class-string  $target
     * @return CastableObjectField
     */
    public static function object(string $target): CastableObjectField
    {
        $pairFactory = new ObjectTypePairFactory();

        return new CastableObjectField(
            $pairFactory->handler(),
            $pairFactory->definition($target)
        );
    }

    /**
     * @param  list<class-string<LexiconContract>>  $refs
     * @return CastableUnionField
     */
    public static function union(array $refs): CastableUnionField
    {
        $pairFactory = new UnionTypePairFactory();

        return new CastableUnionField(
            $pairFactory->handler(),
            $pairFactory->definition($refs)
        );
    }
}
