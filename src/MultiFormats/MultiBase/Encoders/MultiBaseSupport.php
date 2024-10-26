<?php

namespace Atproto\MultiFormats\MultiBase\Encoders;

trait MultiBaseSupport
{
    private function unprefixed(string $data): string
    {
        if ($pos = strpos($data, $this->prefix())) {
            $offset = $pos + strlen($this->prefix());

            return substr($data, $offset);
        }

        return $data;
    }

    private function prefixed(string $data): string
    {
        return $this->prefix() . $data;
    }
}
