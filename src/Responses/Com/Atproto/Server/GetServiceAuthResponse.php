<?php

namespace Atproto\Responses\Com\Atproto\Server;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\BaseResponse;

/**
 * @method string token()
 */
class GetServiceAuthResponse implements ResponseContract
{
    use BaseResponse;
}
