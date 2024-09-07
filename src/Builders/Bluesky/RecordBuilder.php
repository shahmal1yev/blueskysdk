<?php

namespace Atproto\Builders\Bluesky;

use Atproto\Contracts\RecordBuilderContract;
use DateTimeImmutable;
use InvalidArgumentException;
use stdClass;

/**
 * Class RecordBuilder
 *
 * This class is responsible for building records for Bluesky.
 */
class RecordBuilder implements RecordBuilderContract
{
    protected $record;

    /**
     * @var string Regular expression pattern for matching URLs.
     */
    protected static string $urlRegex = '/\b(?:https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]/i';

    /**
     * RecordBuilder constructor.
     */
    public function __construct()
    {
        $this->record = new stdClass();
        $this->record->text = "";

        return $this;
    }

    /**
     * Adds text to the record.
     *
     * @param string $text The text to be added.
     *
     * @return $this
     * @throws InvalidArgumentException
     *
     * @deprecated This method deprecated and will be removed in a future version. Use `text()` instead.
     */
    public function addText($text)
    {
        trigger_error(
            "This method deprecated and will be removed in a future version. Use `text()` instead.",
            E_USER_DEPRECATED
        );

        if (! is_string($text))
            throw new InvalidArgumentException("'text' must be string");

        $this->record->text = (string) $this->record->text . "$text\n";

        preg_match_all(
            self::$urlRegex,
            (string) $this->record->text,
            $urlMatches,
            PREG_OFFSET_CAPTURE
        );

        if (! empty($urlMatches))
            $this->record->facets = [];

        foreach($urlMatches[0] as $match)
        {
            $url = $match[0];
            $startPos = $match[1];
            $endPos = $startPos + strlen($url);

            $this->record->facets[] = [
                "index" => [
                    "byteStart" => $startPos,
                    "byteEnd" => $endPos,
                ],
                "features" => [
                    [
                        "\$type" => "app.bsky.richtext.facet#link",
                        "uri" => $url
                    ]
                ]
            ];
        }

        return $this;
    }

    public function text($text)
    {
        return $this->addText($text);
    }

    /**
     * Adds type to the record.
     *
     * @param string $type The type to be added.
     *
     * @return $this
     * @throws InvalidArgumentException
     *
     * @deprecated This method deprecated and will be removed in a future version. Use `type()` instead.
     */
    public function addType($type = 'app.bsky.feed.post')
    {
        trigger_error(
            "This method deprecated and will be removed in a future version. Use `type()` instead.",
            E_USER_DEPRECATED
        );

        if (! is_string($type))
            throw new InvalidArgumentException("'type' must be string");

        $acceptedTypes = ['app.bsky.feed.post'];

        if (! in_array($type, $acceptedTypes))
            throw new InvalidArgumentException(
                "'$type' is not a valid for 'type' value. It can only be one of the following: " . implode(', ', $acceptedTypes)
            );

        $this->record->type = $type;

        return $this;
    }

    public function type($type = 'app.bsky.feed.post')
    {
        return $this->addType($type);
    }

    /**
     * Adds creation date to the record.
     *
     * @param DateTimeImmutable|null $createdAt The creation date to be added.
     *
     * @return $this
     * @throws InvalidArgumentException
     *
     * @deprecated This method deprecated and will be removed in a future version. Use `createdAt()` instead.
     */
    public function addCreatedAt($createdAt = null)
    {
        trigger_error(
            "This method deprecated and will be removed in a future version. Use `createdAt()` instead.",
            E_USER_DEPRECATED
        );

        if (! is_null($createdAt))
        {
            $createdAt = $createdAt->format('c');
        }
        else
        {
            $createdAt = date('c');
        }

        $this->record->createdAt = $createdAt;

        return $this;
    }

    public function createdAt(DateTimeImmutable $createdAt = null)
    {
        return $this->addCreatedAt($createdAt);
    }

    /**
     * Adds image to the record.
     *
     * @param string $blob The image blob.
     * @param string $alt The alternative text for the image.
     *
     * @return $this
     * @throws InvalidArgumentException
     *
     * @deprecated This method deprecated and will be removed in a future version. Use `image()` instead.
     */
    public function addImage($blob, $alt = "")
    {
        trigger_error(
            "This method deprecated and will be removed in a future version. Use `image()` instead.",
            E_USER_DEPRECATED
        );

        if (! is_string($alt))
            throw new InvalidArgumentException("'alt' must be a string");

        if (! isset($this->record->embed))
            $this->record->embed = (object) [
                "\$type" => "app.bsky.embed.images",
                "images" => []
            ];

        $this->record->embed->images[] = [
            "image" => $blob,
            "alt" => $alt
        ];

        return $this;
    }

    public function image($blob, $alt = "")
    {
        return $this->addImage($blob, $alt);
    }

    /**
     * Builds the record.
     *
     * @return stdClass The built record.
     *
     * @deprecated This method deprecated and will be removed in a future version. Use `build()` instead.
     */
    public function buildRecord()
    {
        trigger_error(
            "This method deprecated and will be removed in a future version. Use `build()` instead.",
            E_USER_DEPRECATED
        );

        return $this->record;
    }


    public function build()
    {
        return $this->buildRecord();
    }
}
