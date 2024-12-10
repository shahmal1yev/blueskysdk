<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Exceptions\BlueskyException;
use Atproto\Lexicons\App\Bsky\RichText\FeatureAbstract;
use Atproto\Lexicons\App\Bsky\RichText\RichText;
use Atproto\Support\Arr;

class FeatureObject implements ObjectContract
{
    use BaseObject;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function cast(): FeatureAbstract
    {
        return $this->instance($this->type());
    }

    private function type(): string
    {
        if ($facetID = Arr::pull($this->content, '$type')) {
            return explode('#', $facetID)[1];
        }

        throw new BlueskyException("Unexpected behavior, '\$type' does not exist.");
    }

    private function instance(string $type): FeatureAbstract
    {
        # Note: The order of parameters differs between the RichText factory methods and the endpoint response.
        # - RichText factory methods expect parameters in the order: reference, label.
        # - The endpoint response provides parameters in the order: label, reference.
        # We extract the '$type' key earlier in the process, so it is not included in the reversed array.

        $a = RichText::$type(...array_reverse(array_values($this->content)));

        return $a;
    }
}
