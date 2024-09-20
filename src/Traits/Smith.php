<?php

namespace Atproto\Traits;

use Atproto\Client;
use Atproto\Contracts\RequestContract;
use Atproto\Exceptions\Http\Request\RequestNotFoundException;

trait Smith
{
    private string $prefix = "Atproto\\HTTP\\API\\Requests\\";
    private array $path = [];

    public function __call(string $name, array $arguments): Client
    {
        $this->path[] = $name;

        return $this;
    }

    /**
     * @throws RequestNotFoundException
     */
    public function forge(...$arguments): RequestContract
    {
        $arguments = array_merge([$this], array_values($arguments));

        $request = $this->request();

        if (! class_exists($request)) {
            throw new RequestNotFoundException("$request class does not exist.");
        }

        $request = new $request(...$arguments);

        $this->refresh();

        return $request;
    }

    public function path(): string
    {
        return implode('\\', array_map(
            'ucfirst',
            $this->path
        ));
    }

    private function refresh(): void
    {
        $this->path = [];
    }

    private function request(): string
    {
        return $this->prefix . $this->path();
    }
}
