<?php

namespace Tests\Unit\Lexicons\Com\Atproto\Label;

use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\Com\Atproto\Label\SelfLabels;
use PHPUnit\Framework\TestCase;
use stdClass;

class SelfLabelsTest extends TestCase
{
    private SelfLabels $selfLabels;
    private int $maxlength = 10;
    private int $maxlengthByItem = 128;

    public function setUp(): void
    {
        $this->selfLabels = new SelfLabels();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function test__constructFillsDataCorrectly(): void
    {
        $expected = array_map(fn (string $val) => ['val' => $val], ['val 1', 'val 2', 'val 3']);

        $this->selfLabels = new SelfLabels(array_column(
            $expected,
            'val'
        ));

        $this->assertEquals($expected, $this->selfLabels->toArray());
    }

    public function test__constructorThrowsInvalidArgumentExceptionWhenPassedInvalidArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("must be of the type string");

        $this->selfLabels = new SelfLabels(['val 1', new stdClass()]);
    }

    public function test__constructThrowsInvalidArgumentExceptionWhenLimitExceeded(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Maximum allowed length is ".$this->maxlength);

        $trigger = str_split(str_pad('', ++$this->maxlength, 'a'));
        $this->selfLabels = new SelfLabels($trigger);
    }

    public function test__constructorThrowsExceptionWhenPassedArgumentThatExceedsLimit(): void
    {
        $trigger = [str_pad('', ++$this->maxlengthByItem, 'a')];
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Length exceeded for " . current($trigger));

        $this->selfLabels = new SelfLabels($trigger);
    }

    public function testAddThrowsExceptionWhenPassedArgumentExceedsMaxLengthLimit(): void
    {
        $trigger = str_split(str_pad('', ++$this->maxlength, 'a'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Maximum allowed length is ".--$this->maxlength);

        foreach($trigger as $item) {
            $this->selfLabels[] = $item;
        }
    }
}
