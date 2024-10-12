<?php

namespace Atproto\Contracts\Lexicons\App\Bsky\Embed;

use Atproto\Lexicons\App\Bsky\Embed\Collections\CaptionCollection;

interface VideoInterface extends EmbedInterface
{
    public function captions(CaptionCollection $captions = null);
    public function alt(string $alt = null);
    public function aspectRatio();
}
