<?php

namespace Tests\Supports;

use Faker\Factory;
use Faker\Generator;

trait AssetTest
{
    protected Generator $faker;

    private static array $falsyValues;

    private static array $primitiveAssets;

    public function setUp(): void
    {
        parent::setUp();

        list($this->faker, static::$falsyValues) = self::getData();
    }

    protected static function getData(): array
    {
        return [
            Factory::create(),
            [
                [],
                false,
                0,
                0.0,
                '',
                null
            ]
        ];
    }

    abstract protected function resource(array $data);
}