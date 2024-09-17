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
    public function forge(): RequestContract
    {
        $namespace = $this->namespace();

        if (! class_exists($namespace)) {
            throw new RequestNotFoundException("$namespace class does not exist.");
        }

        return new $namespace($this);
    }

    protected function namespace(): string
    {
        $namespace = $this->prefix() . implode('\\', array_map(
            'ucfirst',
            $this->path
        ));

        $this->refresh();

        return $namespace;
    }
    abstract public function prefix(): string;
}
