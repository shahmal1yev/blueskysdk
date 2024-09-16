<?php

namespace Atproto;

use Atproto\Contracts\RequestContract;
use Atproto\Exceptions\Http\Request\RequestNotFoundException;
use Exception;

class Client
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
    public function build(): RequestContract
    {
        $namespace = $this->namespace();

        if (! class_exists($namespace)) {
            throw new RequestNotFoundException("$namespace class does not exist.");
        }

        $request = new $namespace;

        $request->build();

        return $request;
    }

    protected function namespace(): string
    {
        $prefix = '\\Atproto\\HTTP\\API\\Requests\\';
        $namespace = "$prefix" . implode('\\', array_map(
                'ucfirst',
                $this->path
            ));

        $this->refresh();

        return $namespace;
    }
}
