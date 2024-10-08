<?php

namespace Atproto\Contracts\Lexicons\App\Bsky\RichText;

use Atproto\Collections\FeatureCollection;
use Atproto\Contracts\LexiconBuilder;
use Atproto\Contracts\Stringable;

interface FacetContract extends LexiconBuilder, Stringable
{
    public function features(): FeatureCollection;
    public function byteSlice(): ByteSliceContract;
}
