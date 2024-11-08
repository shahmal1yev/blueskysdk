<?php

namespace Atproto\Lexicons\Com\Atproto\Label;

use Atproto\Contracts\SerializableContract;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\Traits\Serializable;
use GenericCollection\GenericCollection;

class SelfLabels extends GenericCollection implements SerializableContract
{
    use Serializable;

    private const MAXLENGTH = 10;
    private const MAXLENGTH_BY_ITEM = 128;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(iterable $collection = [])
    {
        $this->collection = $collection;

        try {
            parent::__construct($this->validator(), $collection);
        } catch (\TypeError|\GenericCollection\Exceptions\InvalidArgumentException $e) {
            $this->throw($e);
        }
    }

    private function validator(): \Closure
    {
        return function (string $val): bool {
            if ($this->count() + 1 > self::MAXLENGTH) {
                throw new InvalidArgumentException("Maximum allowed length is " . self::MAXLENGTH);
            }

            if (strlen($val) > self::MAXLENGTH_BY_ITEM) {
                throw new InvalidArgumentException("Length exceeded for $val. Max ".self::MAXLENGTH_BY_ITEM." characters allowed.");
            }

            return true;
        };
    }

    /**
     * @throws InvalidArgumentException
     */
    private function throw($exception): void
    {
        throw new InvalidArgumentException(
            str_replace(
                "::".__NAMESPACE__."\{closure}()",
                "",
                $exception->getMessage()
            ),
            0,
            $exception
        );
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return array_map(fn (string $val) => ['val' => $val], $this->collection);
    }
}
