<?php

namespace Atproto\Responses\Com\Atproto\Server;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\BaseResponse;

/**
 * @method string accessJwt()
 * @method string refreshJwt()
 * @method string handle()
 * @method string did()
 * @method string didDoc()
 * @method string email()
 * @method bool emailConfirmed()
 * @method bool emailAuthFactor()
 * @method bool active()
 * @method string|null status() The status of the account. Possible values are 'takendown', 'suspended', 'deactivated'. If `active` is `false`, this field may provide a reason for the account's inactivity.
 */
class CreateSessionResponse implements ResponseContract
{
    use BaseResponse;
}
