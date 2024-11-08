<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Responses\Objects\DatetimeObject;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\AssetTest;

class DatetimeObjectTest extends TestCase
{
    use AssetTest;

    public function testConstructorReturnsCorrectInstance(): void
    {
        $instance = $this->resource([]);

        $actual = $instance;
        $expected = DatetimeObject::class;

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCastReturnsCarbonInstance(): void
    {
        $instance = $this->resource([$this->faker->dateTime->format(DATE_ATOM)]);

        $actual = $instance->cast();
        $expected = Carbon::class;

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCastThrowsExceptionWhenPassingInvalidDateFormat(): void
    {
        $this->expectException(InvalidFormatException::class);

        $this->resource(['invalid-date']);
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): DatetimeObject
    {
        return new DatetimeObject(current($data));
    }
}
