<?php

namespace Atproto\Lexicons\App\Bsky\Actor\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\FieldTypes\FieldType;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Traits\Castable;

/**
 * @method integer count
 * @method list<ProfileViewBasic> followers
 */
class KnownFollowers implements DefinitionContract
{
    use Castable;
    use BaseObject;

    protected function casts(): array
    {
        return [
            'followers' => FieldType::array(FieldType::object(ProfileViewBasic::class))
                ->maxLength(5)
                ->minLength(0)
        ];
    }

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.actor.defs#knownFollowers';
    }
}
