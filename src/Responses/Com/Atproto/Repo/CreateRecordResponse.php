<?php

namespace Atproto\Responses\Com\Atproto\Repo;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\BaseResponse;

/**
 * @method string uri()
 * @method string cid()
 * @method string validationStatus()
 */
class CreateRecordResponse implements ResponseContract
{
    use BaseResponse;
}
