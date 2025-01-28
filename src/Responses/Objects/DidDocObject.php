<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Traits\Castable;
use Illuminate\Support\Collection;

/**
 * @method Collection context()
 * @method Collection alsoKnownAs()
 * @method Collection service()
 * @method Collection verificationMethod()
 */
class DidDocObject implements ObjectContract
{
    use BaseObject;
    use Castable;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    protected function casts(): array
    {
        return [
            '@context' => ArrayObject::class,
            'alsoKnownAs' => ArrayObject::class,
            'service' => ArrayObject::class,
            'verificationMethod' => ArrayObject::class,
        ];
    }
}
