<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

use Atproto\Collections\FeatureCollection;
use Atproto\Contracts\Lexicons\App\Bsky\RichText\ByteSliceContract;
use Atproto\Contracts\Lexicons\App\Bsky\RichText\FacetContract;
use Atproto\Lexicons\Traits\Lexicon;

class Facet implements FacetContract
{
    use Lexicon;

    private ByteSliceContract $byteSlice;

    private FeatureCollection $features;

    /**
     * @param  FeatureCollection  $features
     * @param  ByteSliceContract  $byteSlice
     */
    public function __construct(FeatureCollection $features, ByteSliceContract $byteSlice)
    {
        $this->features = $features;
        $this->byteSlice = $byteSlice;
    }

    public function features(): FeatureCollection
    {
        return $this->features;
    }

    public function byteSlice(): ByteSliceContract
    {
        return $this->byteSlice;
    }

    public function jsonSerialize(): array
    {
        return [
            'index' => $this->byteSlice,
            'features' => $this->features->toArray()
        ];
    }
}
