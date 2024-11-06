<?php

namespace Atproto\Responses\Com\Atproto\Repo;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\BaseResponse;

/**
 * @method object blob()
 */
class UploadBlobResponse implements ResponseContract
{
    use BaseResponse;
}
