<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Responses\BaseResponse;

trait BaseObject
{
    use BaseResponse;

    /** @var mixed */
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
        $this->cast();
    }

    /**
     * Cast the object to its contract representation.
     *
     * @deprecated 1.5.0-beta This method is deprecated and will be removed in version 2.x.
     */
    public function cast(): ObjectContract
    {
        trigger_error(
            sprintf(
                'The method %s::cast() is deprecated since version 1.5.0-beta and will be removed in version 2.x.',
                __CLASS__
            ),
            E_USER_DEPRECATED
        );

        return $this;
    }

    /**
     * Revert the value to its original state.
     *
     * @deprecated 1.5.0-beta This method is deprecated and will be removed in version 2.x.
     */
    public function revert()
    {
        trigger_error(
            sprintf(
                'The method %s::revert() is deprecated since version 1.5.0-beta and will be removed in version 2.x.',
                __CLASS__
            ),
            E_USER_DEPRECATED
        );

        return $this->value;
    }
}
