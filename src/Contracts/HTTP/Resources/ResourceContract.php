<?php

namespace Atproto\Contracts\HTTP\Resources;

use Atproto\API\App\Bsky\Actor\GetProfile;
use Atproto\API\Com\Atrproto\Repo\CreateRecord;
use Atproto\API\Com\Atrproto\Repo\UploadBlob;
use Atproto\Exceptions\Resource\BadAssetCallException;

/**
 * @see GetProfile
 * @see CreateRecord
 * @see UploadBlob
 */
interface ResourceContract
{
    /**
     * @param  string  $name
     * @return mixed
     *
     * @throws BadAssetCallException If the asset does not exist on resource.
     */
    public function get(string $name);

    /**
     * @param  string  $name
     * @return bool
     */
    public function exist(string $name): bool;
}
