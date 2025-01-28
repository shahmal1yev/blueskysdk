<?php

namespace Atproto\Responses\Com\Atproto\Server;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\BaseResponse;
use Atproto\Responses\Objects\DidDocObject;
use Atproto\Traits\Castable;

/**
 * @method string accessJwt()
 * @method string refreshJwt()
 * @method string handle()
 * @method string did()
 * @method DidDocObject didDoc()
 * @method string email()
 * @method bool emailConfirmed()
 * @method bool emailAuthFactor()
 * @method bool active()
 * @method string|null status() The status of the account. Possible values are 'takendown', 'suspended', 'deactivated'. If `active` is `false`, this field may provide a reason for the account's inactivity.
 */
class CreateSessionResponse implements ResponseContract
{
    use BaseResponse;
    use Castable;

    protected function casts(): array
    {
        return [
            'didDoc' => DidDocObject::class,
        ];
    }
}
