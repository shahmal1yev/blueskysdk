<?php

namespace Tests\Unit\Lexicons\App\Bsky\Actor\Defs;

use Atproto\Lexicons\App\Bsky\Actor\Defs\KnownFollowers;
use Atproto\Lexicons\App\Bsky\Actor\Defs\ProfileViewBasic;
use Atproto\Traits\Castable;
use PHPUnit\Framework\TestCase;

class KnownFollowersTest extends TestCase
{
    public function test_nsid_is_correct(): void
    {
        $this->assertSame(
            'app.bsky.actor.defs#knownFollowers',
            KnownFollowers::nsid()
        );

        $this->assertSame(
            'app.bsky.actor.defs#knownFollowers',
            (new KnownFollowers([]))->nsid()
        );
    }

    public function test_followers_can_cast_data_correctly(): void
    {
        $data = ['followers' => [['did' => 'foo', 'handle' => 'bar']]];
        $followers = (new KnownFollowers($data))->followers();

        $this->assertNotEmpty($followers);
        $this->assertIsIterable($followers);
        $this->assertInstanceOf(ProfileViewBasic::class, $followers[0]);
        $this->assertSame($followers[0]->handle(), 'bar');
    }
}
