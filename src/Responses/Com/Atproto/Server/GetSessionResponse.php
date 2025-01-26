<?php

namespace Atproto\Responses\Com\Atproto\Server;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\BaseResponse;

/**
 * @method string handle()
 * @method string did()
 * @method string email()
 * @method bool emailConfirmed()
 * @method bool emailAuthFactor()
 * @method didDoc()
 * @method bool active()
 * @method string status()
 */
class GetSessionResponse implements ResponseContract
{
    use BaseResponse;
}
