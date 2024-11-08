<?php

namespace Atproto\Support;

use Atproto\Exceptions\InvalidArgumentException;

trait Enum
{
    public string $name;
    public $value;

    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    private static function instance($name, $value): self
    {
        $static = static::class;
        $instance = null;

        eval("\$instance = new class(\$name, \$value) extends $static {};");

        return $instance;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function get(string $name): self
    {
        self::validate($name);

        return self::instance($name, self::CONSTANTS[$name]);
    }

    /**
     * @throws InvalidArgumentException
     */
    private static function validate(string $name): void
    {
        if (! isset(self::CONSTANTS[$name])) {
            throw new InvalidArgumentException("'$name' is not implemented");
        }
    }
}
