<?php

namespace Atproto\Traits;

use Atproto\Client;
use Atproto\Contracts\RequestContract;
use Atproto\Exceptions\Http\Request\RequestNotFoundException;

trait Smith
{
    protected array $path = [];

    public function __call(string $name, array $arguments): Client
    {
        $this->path[] = $name;

        return $this;
    }

    protected function refresh(): void
    {
        $this->path = [];
    }

    /**
     * @throws RequestNotFoundException
     */
    public function forge(array $arguments = []): RequestContract
    {
        $namespace = $this->namespace();

        if (! class_exists($namespace)) {
            throw new RequestNotFoundException("$namespace class does not exist.");
        }

        array_unshift($arguments, self::$prefix);

        return new $namespace(...$arguments);
    }

    protected function namespace(): string
    {
        $namespace = self::$prefix . implode('\\', array_map(
                'ucfirst',
                $this->path
            ));

        $this->refresh();

        return $namespace;
    }
}