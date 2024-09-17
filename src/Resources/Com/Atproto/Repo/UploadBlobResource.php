<?php

namespace Atproto\Resources\Com\Atproto\Repo;

use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\BaseResource;

class UploadBlobResource implements ResourceContract
{
    use BaseResource;

    protected function casts(): array
    {
        return [];
    }
}