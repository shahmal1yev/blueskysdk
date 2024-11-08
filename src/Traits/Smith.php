<?php

namespace Atproto\Traits;

use Atproto\Client;
use Atproto\Contracts\Lexicons\APIRequestContract;
use Atproto\Contracts\Observer;
use Atproto\Exceptions\Http\Request\LexiconNotFoundException;

trait Smith
{
    private string $prefix = "Atproto\\Lexicons\\";
    private array $path = [];

    public function __call(string $name, array $arguments): Client
    {
        $this->path[] = $name;

        return $this;
    }

    /**
     * @throws LexiconNotFoundException
     */
    public function forge(...$arguments)
    {
        $arguments = array_merge([$this], array_values($arguments));

        $request = $this->request();

        if (! class_exists($request)) {
            throw new LexiconNotFoundException("$request class does not exist.");
        }

        /** @var APIRequestContract $request */
        $request = new $request(...$arguments);

        if ($request instanceof \SplObserver) {
            $this->attach($request);
        }

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
