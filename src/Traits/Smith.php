<?php

namespace Atproto\Traits;

use Atproto\Client;
use Atproto\Exceptions\Http\Request\LexiconNotFoundException;
use Atproto\Factories\HTTPFactory;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Lexicons\Traits\Endpoint;

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
        $this->subscribe($instance = $this->instantiate($this->arguments($arguments)));
        $this->refresh();

        return $instance;
    }

    private function path(): string
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

    /**
     * @throws LexiconNotFoundException
     */
    private function namespace(): string
    {
        if (! class_exists($namespace = $this->prefix . $this->path())) {
            throw new LexiconNotFoundException("$namespace lexicon does not exist.");
        }

        return $namespace;
    }

    /**
     * @throws LexiconNotFoundException
     */
    private function instantiate(array $arguments = []): object
    {
        return new ($this->namespace())(...array_values($arguments));
    }
    
    private function subscribe($instance): void
    {
        if (in_array(AuthenticatedEndpoint::class, class_uses_recursive($instance))) {
            $this->authenticated->attach($instance);
        }
    }

    private function arguments(array $arguments): array
    {
        return array_merge(array_values($arguments), $this->dependencies());
    }

    private function dependencies(): array
    {
        $uses = class_uses_recursive($this->namespace());
        $dependencies = [];

        if (in_array(Endpoint::class, $uses, true)) {
            $dependencies[] = new HTTPFactory();
        }

        if (in_array(AuthenticatedEndpoint::class, $uses, true)) {
            $dependencies[] = $this->authenticated;
        }

        return $dependencies;
    }
}
