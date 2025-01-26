<?php

namespace Atproto;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\MediaContract;
use Atproto\DataModel\Blob\Blob;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Actor\GetProfile;
use Atproto\Lexicons\App\Bsky\Actor\GetProfiles;
use Atproto\Lexicons\App\Bsky\Embed\Collections\ImageCollection;
use Atproto\Lexicons\App\Bsky\Embed\External;
use Atproto\Lexicons\App\Bsky\Embed\Image;
use Atproto\Lexicons\App\Bsky\Embed\Record;
use Atproto\Lexicons\App\Bsky\Embed\RecordWithMedia;
use Atproto\Lexicons\App\Bsky\Embed\Video;
use Atproto\Lexicons\App\Bsky\Feed\GetTimeline;
use Atproto\Lexicons\App\Bsky\Feed\Post;
use Atproto\Lexicons\App\Bsky\Feed\SearchPosts;
use Atproto\Lexicons\App\Bsky\Graph\GetFollowers;
use Atproto\Lexicons\Com\Atproto\Repo\CreateRecord;
use Atproto\Lexicons\Com\Atproto\Repo\StrongRef;
use Atproto\Lexicons\Com\Atproto\Repo\UploadBlob;
use Atproto\Lexicons\Com\Atproto\Server\CreateSession;
use Atproto\Lexicons\Com\Atproto\Server\GetSession;
use Atproto\Lexicons\Com\Atproto\Server\RefreshSession;
use Atproto\Responses\Com\Atproto\Server\CreateSessionResponse;

class BskyFacade
{
    private static ?self $instance = null;
    private Client $client;

    private function __construct()
    {
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * @throws InvalidArgumentException If `$client` argument not set on first time.
     */
    public static function getInstance(Client $client = null): self
    {
        if (! self::$instance) {
            self::$instance = new self();
        }

        if (! $client && ! isset(self::$instance->client)) {
            throw new InvalidArgumentException('Client not configured: Please set a valid Client instance on the first call.');
        }

        if ($client) {
            self::$instance->client = $client;
        }

        return self::$instance;
    }

    /**
     * Get detailed profile view of an actor. Does not require auth, but contains relevant metadata with auth.
     *
     * @return GetProfile
     */
    public function getProfile(): GetProfile
    {
        return $this->client->app()->bsky()->actor()->getProfile()->forge();
    }

    /**
     * Get detailed profile views of multiple actors.
     *
     * @return GetProfiles
     */
    public function getProfiles(): GetProfiles
    {
        return $this->client->app()->bsky()->actor()->getProfiles()->forge();
    }

    /**
     * Get detailed profile views of multiple actors.
     *
     * @return GetTimeline
     */
    public function getTimeline(): GetTimeline
    {
        return $this->client->app()->bsky()->feed()->getTimeline()->forge();
    }

    /**
     * Record containing a Bluesky post.
     *
     * @return Post
     */
    public function post(): Post
    {
        return $this->client->app()->bsky()->feed()->post()->forge();
    }

    /**
     * Alias for `post`.
     *
     * @return Post
     */
    public function record(): Post
    {
        return $this->post();
    }

    /**
     * Find posts matching search criteria, returning views of those posts.
     *
     * @param  string  $query Required. Search query string; syntax, phrase, boolean, and faceting is unspecified,
     *                        but Lucene query syntax is recommended.
     * @return SearchPosts
     */
    public function searchPosts(string $query): SearchPosts
    {
        return $this->client->app()->bsky()->feed()->searchPosts()->forge($query);
    }

    /**
     * Enumerates accounts which follow a specified account (actor).
     *
     * @return GetFollowers
     */
    public function getFollowers(): GetFollowers
    {
        return $this->client->app()->bsky()->graph()->getFollowers()->forge();
    }

    /**
     * Create a single new repository record. Requires auth, implemented by PDS.
     *
     * @return CreateRecord
     */
    public function createRecord(): CreateRecord
    {
        return $this->client->com()->atproto()->repo()->createRecord()->forge();
    }

    /**
     * Upload a new blob, to be referenced from a repository record. The blob will be deleted if it is not
     * referenced within a time window (eg, minutes). Blob restrictions (mimetype, size, etc) are enforced when the
     * reference is created. Requires auth, implemented by PDS.
     *
     * @return UploadBlob
     */
    public function uploadBlob(): UploadBlob
    {
        return $this->client->com()->atproto()->repo()->uploadBlob()->forge();
    }

    /**
     * Create an authentication session.
     *
     * @param  string  $identifier
     * @param  string  $password
     * @param  CreateSessionResponse|null  $session
     * @return CreateSession
     */
    public function createSession(string $identifier, string $password, CreateSessionResponse $session = null): CreateSession
    {
        return $this->client->com()->atproto()->server()->createSession()->forge(
            $identifier,
            $password,
            $session
        );
    }

    /**
     * Get information about the current auth session. Requires auth.
     *
     * @return GetSession
     */
    public function getSession(): GetSession
    {
        return $this->client->com()->atproto()->server()->getSession()->forge();
    }

    /**
     * Refresh an authentication session. Requires auth using the 'refreshJwt' (not the 'accessJwt').
     *
     * @param  string|null  $token
     * @return RefreshSession
     */
    public function refreshSession(string $token = null): RefreshSession
    {
        return $this->client->com()->atproto()->server()->refreshSession()->forge($token);
    }

    /**
     * A representation of some externally linked content (eg, a URL and 'card'), embedded in a Bluesky record (eg,
     * a post).
     *
     * @param  string  $uri
     * @param  string  $title
     * @param  string  $description
     * @return External
     */
    public function externalEmbed(string $uri, string $title, string $description): External
    {
        return $this->client->app()->bsky()->embed()->external()->forge(
            $uri, $title, $description
        );
    }

    /**
     * Image instance for `ImageCollection` object.
     *
     * @param  Blob  $blob
     * @param  string  $alt
     * @return Image
     * @throws InvalidArgumentException
     */
    public function image(Blob $blob, string $alt): Image
    {
        return new Image($blob, $alt);
    }

    /**
     * A set of images embedded in a Bluesky record (eg, a post).
     *
     * @param  Image  ...$imageCollection
     * @return ImageCollection
     */
    public function imagesEmbed(Image ...$imageCollection): ImageCollection
    {
        return new ImageCollection($imageCollection);
    }

    /**
     * A representation of a record embedded in a Bluesky record (eg, a post). For example, a quote-post, or sharing
     * a feed generator record.
     *
     * @param  StrongRef  $ref
     * @return Record
     */
    public function recordEmbed(StrongRef $ref): Record
    {
        return $this->client->app()->bsky()->embed()->record()->forge($ref);
    }

    /**
     * A representation of a record embedded in a Bluesky record (eg, a post), alongside other compatible embeds.
     * For example, a quote post and image, or a quote post and external URL card.
     *
     * @param  Record  $record
     * @param  MediaContract  $media
     * @return RecordWithMedia
     */
    public function recordWithMediaEmbed(Record $record, MediaContract $media): RecordWithMedia
    {
        return $this->client->app()->bsky()->embed()->recordWithMedia()->forge($record, $media);
    }

    /**
     * A video embedded in a Bluesky record (eg, a post).
     *
     * @param  Blob  $blob
     * @return Video
     */
    public function videoEmbed(Blob $blob): Video
    {
        return $this->client->app()->bsky()->embed()->video()->forge($blob);
    }
}
