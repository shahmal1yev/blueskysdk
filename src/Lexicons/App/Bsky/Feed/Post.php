<?php

namespace Atproto\Lexicons\App\Bsky\Feed;

use Atproto\Collections\FacetCollection;
use Atproto\Collections\FeatureCollection;
use Atproto\Contracts\Lexicons\App\Bsky\Feed\PostBuilderContract;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\RichText\ByteSlice;
use Atproto\Lexicons\App\Bsky\RichText\Facet;
use Atproto\Lexicons\App\Bsky\RichText\FeatureAbstract;
use Atproto\Lexicons\App\Bsky\RichText\FeatureFactory;
use Carbon\Carbon;
use DateTimeImmutable;

class Post implements PostBuilderContract
{
    private const TYPE_NAME = 'app.bsky.feed.post';
    private const TEXT_LIMIT = 3000;

    private string $text = '';
    private ?DateTimeImmutable $createdAt = null;
    private FacetCollection $facets;

    public function __construct()
    {
        $this->facets = new FacetCollection();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function text(...$items): PostBuilderContract
    {
        foreach ($items as $index => $item) {
            $this->validate($item, $index);
            $this->processItem($item);
        }

        return $this;
    }

    /**
     * Adds a tag to the post.
     *
     * @throws InvalidArgumentException
     */
    public function tag(string $reference, string $label = null): PostBuilderContract
    {
        return $this->addFeatureItem('tag', $reference, $label);
    }

    /**
     * Adds a link to the post.
     *
     * @throws InvalidArgumentException
     */
    public function link(string $reference, string $label = null): PostBuilderContract
    {
        return $this->addFeatureItem('link', $reference, $label);
    }

    /**
     * Adds a mention to the post.
     *
     * @throws InvalidArgumentException
     */
    public function mention(string $reference, string $label = null): PostBuilderContract
    {
        return $this->addFeatureItem('mention', $reference, $label);
    }

    public function embed(...$embeds): PostBuilderContract
    {
        return $this;
    }

    public function createdAt(DateTimeImmutable $dateTime): PostBuilderContract
    {
        $this->createdAt = $dateTime;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            '$type' => self::TYPE_NAME,
            'createdAt' => $this->getFormattedCreatedAt(),
            'text' => $this->text,
            'facets' => $this->facets->toArray(),
        ];
    }

    public function __toString(): string
    {
        return json_encode($this);
    }

    private function isString($item): bool
    {
        return is_scalar($item);
    }

    private function isFeature($item): bool
    {
        return $item instanceof FeatureAbstract;
    }

    /**
     * Validates the given item.
     *
     * @throws InvalidArgumentException
     */
    private function validate($item, int $index = 0): void
    {
        if (!$this->isString($item) && !$this->isFeature($item)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Argument at index %d is invalid: must be a string or an instance of %s.',
                    $index + 1,
                    FeatureAbstract::class
                )
            );
        }

        if ($this->isString($item) && mb_strlen($item) > self::TEXT_LIMIT) {
            throw new InvalidArgumentException(
                sprintf(
                    'Text must be less than or equal to %d characters.',
                    self::TEXT_LIMIT
                )
            );
        }
    }

    /**
     * Processes the item, adding it to the post text or as a feature.
     * @throws InvalidArgumentException
     */
    private function processItem($item): void
    {
        if ($this->isString($item)) {
            $this->addString($item);
        } else {
            $this->addFeature($item);
        }
    }

    /**
     * Adds a feature item like tag, link, or mention to the post.
     *
     * @throws InvalidArgumentException
     */
    private function addFeatureItem(string $type, string $reference, ?string $label): PostBuilderContract
    {
        $feature = FeatureFactory::{$type}($reference, $label);
        $this->addFeature($feature);

        return $this;
    }

    /**
     * Adds a string to the post text.
     *
     * @param  string  $string
     */
    private function addString(string $string): void
    {
        $this->text .= $string;
    }

    /**
     * Adds a feature to the post.
     *
     * @throws InvalidArgumentException
     */
    private function addFeature(FeatureAbstract $feature): void
    {
        $label = (string) $feature;
        $this->text .= $label;

        try {
            $facet = new Facet(
                new FeatureCollection([$feature]),
                new ByteSlice($this->text, $label)
            );
            $this->facets[] = $facet;
        } catch (\GenericCollection\Exceptions\InvalidArgumentException $e) {
            throw new InvalidArgumentException(
                sprintf('Feature must be an instance of %s.', FeatureAbstract::class)
            );
        }
    }

    /**
     * Returns the formatted creation date.
     */
    private function getFormattedCreatedAt(): string
    {
        $createdAt = $this->createdAt ?: Carbon::now();

        return $createdAt->format(DATE_ATOM);
    }
}
