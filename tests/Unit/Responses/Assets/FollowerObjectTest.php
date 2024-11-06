<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Responses\Objects\FollowerObject;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\UserAssetTest;

class FollowerObjectTest extends TestCase
{
    use UserAssetTest;

    /**
     * @param  array  $data
     * @return FollowerObject
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): FollowerObject
    {
        return new FollowerObject($data);
    }
}
