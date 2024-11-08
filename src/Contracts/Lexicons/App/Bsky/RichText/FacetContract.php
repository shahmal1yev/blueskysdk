<?php

namespace Atproto\Contracts\Lexicons\App\Bsky\RichText;

use Atproto\Collections\FeatureCollection;
use Atproto\Contracts\LexiconContract;

interface FacetContract extends LexiconContract
{
    public function features(): FeatureCollection;
    public function byteSlice(): ByteSliceContract;
}
