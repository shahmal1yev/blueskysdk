<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Responses\Objects\BlockingByListObject;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\ByListAssetTest;

class BlockingByListObjectTest extends TestCase
{
    use ByListAssetTest;

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): BlockingByListObject
    {
        return new BlockingByListObject($data);
    }
}
