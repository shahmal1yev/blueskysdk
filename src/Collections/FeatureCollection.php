<?php

namespace Atproto\Collections;

use Atproto\Lexicons\App\Bsky\RichText\FeatureAbstract;
use GenericCollection\GenericCollection;

class FeatureCollection extends GenericCollection
{
    public function __construct(iterable $features = [])
    {
        parent::__construct(fn ($feature) => $feature instanceof FeatureAbstract, $features);
    }
}
