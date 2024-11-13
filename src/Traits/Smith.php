<?php

namespace Atproto\Traits;

use Atproto\Client;
use Atproto\Contracts\Observer;
use Atproto\Exceptions\Http\Request\LexiconNotFoundException;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;

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
        $this->subscribe($instance = $this->instantiate($arguments));
        $this->refresh();

        return $instance;
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

    private function namespace(): string
    {
        return $this->prefix . $this->path();
    }

    /**
     * @throws LexiconNotFoundException
     */
    private function instantiate(array $arguments = []): object
    {
        if (! $namespace = $this->namespace()) {
            throw new LexiconNotFoundException("$namespace lexicon does not exist.");
        }
        
        return new $namespace(...array_values($arguments));
    }
    
    private function subscribe($instance): void
    {
        if (in_array(AuthenticatedEndpoint::class, class_uses_recursive($instance))) {
            $this->attach($instance);
        }
    }
}
