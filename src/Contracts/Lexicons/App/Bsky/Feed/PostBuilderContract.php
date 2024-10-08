<?php

namespace Atproto\Contracts\Lexicons\App\Bsky\Feed;

use Atproto\Contracts\LexiconBuilder;
use Atproto\Contracts\Stringable;
use Atproto\Lexicons\App\Bsky\RichText\Link;
use Atproto\Lexicons\App\Bsky\RichText\Mention;
use Atproto\Lexicons\App\Bsky\RichText\Tag;
use DateTimeImmutable;

interface PostBuilderContract extends LexiconBuilder, Stringable
{
    public function text(...$items): PostBuilderContract;

    public function tag(string $reference, string $label = null): PostBuilderContract;

    public function link(string $reference, string $label = null): PostBuilderContract;

    public function mention(string $reference, string $label = null): PostBuilderContract;

    public function createdAt(DateTimeImmutable $dateTime): PostBuilderContract;

    public function embed(...$embeds): PostBuilderContract;
}
