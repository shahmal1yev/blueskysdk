<?php

namespace Atproto\Responses\App\Bsky\Video;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\BaseResponse;
use Atproto\Responses\Objects\JobStatusObject;
use Atproto\Traits\Castable;

/**
 * @method JobStatusObject jobStatus()
 */
class GetJobStatusResponse implements ResponseContract
{
    use BaseResponse;
    use Castable;

    protected function casts(): array
    {
        return [
            'jobStatus' => JobStatusObject::class,
        ];
    }
}
