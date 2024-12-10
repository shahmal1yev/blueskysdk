<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Lexicons\App\Bsky\RichText\FeatureAbstract;
use Closure;
use GenericCollection\GenericCollection;

class FeaturesObject extends GenericCollection implements ObjectContract
{
    use CollectionObject;

    protected function item($data): ObjectContract
    {
        return new FeatureObject($data);
    }

    protected function type(): Closure
    {
        return fn ($value): bool => $value instanceof FeatureAbstract;
    }
}
