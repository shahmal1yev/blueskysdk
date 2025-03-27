<?php

namespace Atproto\Lexicons\App\Bsky\Embed\Images;

use Atproto\Contracts\DefinitionContract;
use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Responses\Objects\CollectionObject;
use GenericCollection\GenericCollection;

class View extends GenericCollection implements DefinitionContract
{
    use CollectionObject;

    private const MAX_LENGTH = 4;

    public function __construct(array $content)
    {
        $this->value = $content;
        $this->content = $content;

        parent::__construct(
            $this->type(),
            array_map(function (array $data) {
                return @$this->item($data)->cast();
            }, $this->value['images'])
        );
    }

    protected function item($data): ObjectContract
    {
        return new ViewImage($data);
    }

    protected function type(): \Closure
    {
        return static fn ($value): bool => $value instanceof ViewImage;
    }

    public function validate($value): bool
    {
        return parent::validate($value) && count($this->content['images']) <= self::MAX_LENGTH;
    }

    public static function nsid(): string
    {
        return 'app.bsky.embed.images#view';
    }
}
