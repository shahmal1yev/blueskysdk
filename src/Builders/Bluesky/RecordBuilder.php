<?php

namespace Atproto\Builders\Bluesky;

use Atproto\Contracts\RecordBuilderContract;
use InvalidArgumentException;
use stdClass;

/**
 * Class RecordBuilder
 *
 * This class is responsible for building records for Bluesky.
 */
class RecordBuilder implements RecordBuilderContract
{
    /**
     * @var stdClass Holds the record being built.
     */
    protected $record;

    /**
     * @var string Regular expression pattern for matching URLs.
     */
    protected $urlRegex = '/\b(?:https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]/i';

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
     */
    public function addText($text)
    {
        if (! is_string($text))
            throw new InvalidArgumentException("'text' must be string");

        $this->record->text = (string) $this->record->text . "$text\n";

        preg_match_all(
            $this->urlRegex,
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

    /**
     * Adds type to the record.
     *
     * @param string $type The type to be added.
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addType($type = 'app.bsky.feed.post')
    {
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

    /**
     * Adds creation date to the record.
     *
     * @param string|null $createdAt The creation date to be added.
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addCreatedAt($createdAt = null)
    {
        if (! is_null($createdAt))
        {
            if (date_create_from_format('c', $createdAt) !== false)
                throw new InvalidArgumentException("'$createdAt' must be a valid date. Use 'c' format instead.");
        }
        else
        {
            $createdAt = date('c');
        }

        $this->record->createdAt = $createdAt;

        return $this;
    }

    /**
     * Adds image to the record.
     *
     * @param string $blob The image blob.
     * @param string $alt The alternative text for the image.
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addImage($blob, $alt = "")
    {
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

    /**
     * Builds the record.
     *
     * @return stdClass The built record.
     */
    public function buildRecord()
    {
        return $this->record;
    }
}
