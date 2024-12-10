<?php

namespace Atproto\Factories;

use Atproto\Client;
use Atproto\Contracts\Lexicons\App\Bsky\Embed\EmbedInterface;
use Atproto\Contracts\Lexicons\App\Bsky\Embed\MediaContract;
use Atproto\DataModel\Blob\Blob;
use Atproto\Exceptions\BadMethodCallException;
use Atproto\Lexicons\App\Bsky\Embed\Caption;
use Atproto\Lexicons\App\Bsky\Embed\Collections\ImageCollection;
use Atproto\Lexicons\App\Bsky\Embed\External;
use Atproto\Lexicons\App\Bsky\Embed\Image;
use Atproto\Lexicons\App\Bsky\Embed\Record;
use Atproto\Lexicons\App\Bsky\Embed\RecordWithMedia;
use Atproto\Lexicons\App\Bsky\Embed\Video;
use Atproto\Lexicons\Com\Atproto\Repo\StrongRef;
use Atproto\Support\Arr;

class EmbedFactory
{
    /**
     * @throws BadMethodCallException
     */
    public static function make(string $type, ...$args): EmbedInterface
    {
        $method = preg_split('/[^a-zA-Z0-9_]/', substr($type, strrpos($type, '.') + 1))[0] ?? '';

        try {
            return call_user_func([self::class, $method], ...$args);
        } catch (\TypeError $e) {
            throw new BadMethodCallException("Unsupported embed type: $type", $e->getCode(), $e);
        }
    }

    public static function images(array $images): ImageCollection
    {
        return new ImageCollection(array_map(fn ($imageItem) => self::image($imageItem), $images));
    }

    public static function image(array $imageItem): Image
    {
        return new Image(
            Blob::viaArray(Arr::get($imageItem, 'image')),
            Arr::get($imageItem, 'alt'),
        );
    }

    public static function external(string $uri, string $title, string $description): External
    {
        return (new Client())->app()->bsky()->embed()->external()->forge(
            $uri,
            $title,
            $description
        );
    }

    public static function caption(string $lang, Blob $blob): Caption
    {
        return new Caption($lang, $blob);
    }

    public static function video(Blob $blob): Video
    {
        return new Video($blob);
    }

    public static function record(StrongRef $strongRef): Record
    {
        return new Record($strongRef);
    }

    public static function recordWithMedia(Record $record, MediaContract $media): RecordWithMedia
    {
        return new RecordWithMedia($record, $media);
    }
}
