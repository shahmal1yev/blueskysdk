<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Responses\Objects\MutedByListObject;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\ByListAssetTest;

class MutedByListObjectTest extends TestCase
{
    use ByListAssetTest;

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): MutedByListObject
    {
        return new MutedByListObject($data);
    }
}
