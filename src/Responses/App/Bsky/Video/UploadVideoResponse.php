<?php

namespace Atproto\Responses\App\Bsky\Video;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\BaseResponse;
use Atproto\Responses\Objects\JobStatusObject;
use Atproto\Traits\Castable;

/**
 * @method string jobId()
 * @method string did()
 * @method string state()
 * @method integer progress()
 * @method array blob()
 * @method string error()
 * @method string message()
 */
class UploadVideoResponse implements ResponseContract
{
    use BaseResponse;
}
