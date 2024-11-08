<?php

namespace Atproto\Contracts\Lexicons\App\Bsky\Feed;

use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\App\Bsky\Embed\EmbedInterface;
use Atproto\Lexicons\Com\Atproto\Label\SelfLabels;
use Atproto\Lexicons\Com\Atproto\Repo\StrongRef;
use DateTimeImmutable;

interface PostBuilderContract extends LexiconContract
{
    public function text(...$items): PostBuilderContract;

    public function tag(string $reference, string $label = null): PostBuilderContract;

    public function link(string $reference, string $label = null): PostBuilderContract;

    public function mention(string $reference, string $label = null): PostBuilderContract;

    public function createdAt(DateTimeImmutable $dateTime): PostBuilderContract;

    public function embed(EmbedInterface $embed): PostBuilderContract;

    public function reply(StrongRef $root, StrongRef $parent): PostBuilderContract;

    public function langs(array $languages): PostBuilderContract;

    public function labels(SelfLabels $labels): PostBuilderContract;

    public function tags(array $tags): PostBuilderContract;
}
