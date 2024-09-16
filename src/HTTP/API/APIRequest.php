<?php

namespace Atproto\HTTP\API;

use Atproto\Contracts\HTTP\APIRequestContract;
use Atproto\HTTP\Request;

abstract class APIRequest extends Request implements APIRequestContract
{
    public function __construct(string $prefix = '')
    {
        $this->origin(self::API_BASE_URL)
            ->headers(self::API_BASE_HEADERS);

        if ($prefix) {
            $this->path($this->routePath($prefix));
        }
    }

    private function routePath(string $prefix): string
    {
        $classNamespace = static::class;

        if (strpos($classNamespace, $prefix) === 0) {
            $routePath = substr($classNamespace, strlen($prefix));
        } else {
            $routePath = $classNamespace;
        }

        $routeParts = explode("\\", $routePath);
        $routePath  = array_reduce(
            $routeParts,
            fn ($carry, $part) => $carry .= ".".lcfirst($part)
        );

        return "/xrpc/" . trim($routePath, '.');
    }
}
