<?php

namespace Atproto\Lexicons\App\Bsky\Feed;

use Atproto\Collections\FacetCollection;
use Atproto\Collections\FeatureCollection;
use Atproto\Contracts\Lexicons\App\Bsky\Embed\EmbedInterface;
use Atproto\Contracts\Lexicons\App\Bsky\Feed\PostBuilderContract;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\RichText\ByteSlice;
use Atproto\Lexicons\App\Bsky\RichText\Facet;
use Atproto\Lexicons\App\Bsky\RichText\FeatureAbstract;
use Atproto\Lexicons\App\Bsky\RichText\FeatureFactory;
use Atproto\Lexicons\Com\Atproto\Label\SelfLabels;
use Atproto\Lexicons\Com\Atproto\Repo\StrongRef;
use Carbon\Carbon;
use DateTimeImmutable;

class Post implements PostBuilderContract
{
    private const TYPE_NAME = 'app.bsky.feed.post';
    private const TEXT_LIMIT = 3000;

    private string $text = '';
    private ?DateTimeImmutable $createdAt = null;
    private FacetCollection $facets;
    private ?EmbedInterface $embed = null;
    private ?array $reply = null;
    private ?array $languages = null;
    private ?SelfLabels $labels = null;
    private ?array $tags = null;


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

    public function embed(EmbedInterface $embed = null): PostBuilderContract
    {
        $this->embed = $embed;

        return $this;
    }

    public function reply(StrongRef $root, StrongRef $parent): PostBuilderContract
    {
        $this->reply = [
            'root' => $root,
            'parent' => $parent
        ];

        return $this;
    }

    /**
     * Sets the languages for the post.
     *
     * @param array $languages
     * @return PostBuilderContract
     * @throws InvalidArgumentException
     */
    public function langs(array $languages): PostBuilderContract
    {
        if (count($languages) > 3) {
            throw new InvalidArgumentException('A maximum of 3 language codes is allowed.');
        }

        foreach($languages as $lang) {
            if (! $this->isValidLanguageCode($lang)) {
                throw new InvalidArgumentException(sprintf('Invalid language code: %s', $lang));
            }
        }

        if (empty($languages)) {
            $languages = null;
        }

        $this->languages = $languages;

        return $this;
    }

    public function labels(SelfLabels $labels): PostBuilderContract
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function tags(array $tags): PostBuilderContract
    {
        $maxLength = 8;
        $maxLengthByTag = 640;

        if (count($tags) > $maxLength) {
            throw new InvalidArgumentException('A maximum of 8 tags is allowed.');
        }

        $invalid = array_filter($tags, function ($tag) {
            if (mb_strlen($tag) > 640) {
                return true;
            }
        });

        if (! empty($invalid)) {
            throw new InvalidArgumentException(sprintf(
                "Invalid tags: %s. A tag maximum of %s characters is allowed.",
                implode(', ', $invalid),
                $maxLengthByTag
            ));
        }

        $this->tags = $tags;

        return $this;
    }

    /**
     * Validates the format of a language code.
     *
     * @param string $lang
     * @return bool
     */
    private function isValidLanguageCode(string $lang): bool
    {
        $regular = '(art-lojban|cel-gaulish|no-bok|no-nyn|zh-guoyu|zh-hakka|zh-min|zh-min-nan|zh-xiang)';
        $irregular = '(en-GB-oed|i-ami|i-bnn|i-default|i-enochian|i-hak|i-klingon|i-lux|i-mingo|i-navajo|i-pwn|i-tao|i-tay|i-tsu|sgn-BE-FR|sgn-BE-NL|sgn-CH-DE)';
        $grandfathered = '(' . $irregular . '|' . $regular . ')';
        $privateUse = '(x(-[A-Za-z0-9]{1,8})+)';
        $privateUse2 = '(x(-[A-Za-z0-9]{1,8})+)';
        $singleton = '[0-9A-WY-Za-wy-z]';
        $extension = '(' . $singleton . '(-[A-Za-z0-9]{2,8})+)';
        $variant = '([A-Za-z0-9]{5,8}|[0-9][A-Za-z0-9]{3})';
        $region = '([A-Za-z]{2}|[0-9]{3})';
        $script = '([A-Za-z]{4})';
        $extlang = '([A-Za-z]{3}(-[A-Za-z]{3}){0,2})';
        $language = '(([A-Za-z]{2,3}(-' . $extlang . ')?)|[A-Za-z]{4}|[A-Za-z]{5,8})';
        $langtag = '(' . $language . '(-' . $script . ')?(-' . $region . ')?(-' . $variant . ')*(-' . $extension . ')*(-' . $privateUse . ')?)';
        $languageTag = '(' . $grandfathered . '|' . $langtag . '|' . $privateUse2 . ')';

        $regex = '/^' . $languageTag . '$/';

        return preg_match($regex, $lang);
    }

    public function createdAt(DateTimeImmutable $dateTime): PostBuilderContract
    {
        $this->createdAt = $dateTime;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array_filter([
            '$type' => self::TYPE_NAME,
            'createdAt' => $this->getFormattedCreatedAt(),
            'text' => $this->text,
            'facets' => $this->facets->toArray(),
            'embed' => $this->embed,
            'replyRef' => $this->reply,
            'langs' => $this->languages,
            'labels' => $this->labels,
            'tags' => $this->tags,
        ]);
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

        if ($this->isString($item) && (mb_strlen($item) + mb_strlen($this->text)) > self::TEXT_LIMIT) {
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
            $this->facets[] = new Facet(
                new FeatureCollection([$feature]),
                new ByteSlice($this->text, $label)
            );
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
