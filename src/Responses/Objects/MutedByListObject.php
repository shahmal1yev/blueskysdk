<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Contracts\Resources\ResponseContract;
use Carbon\Carbon;

/**
 * @method string uri()
 * @method string cid()
 * @method string name()
 * @method object purpose()
 * @method string avatar()
 * @method int listItemCount()
 * @method LabelsObject labels()
 * @method ViewerObject viewer()
 * @method Carbon indexedAt()
 */
class MutedByListObject implements ResponseContract, ObjectContract
{
    use ByListAsset;
}
