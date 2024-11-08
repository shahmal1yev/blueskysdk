<?php

namespace Atproto\Contracts;

interface EncoderContract
{
    public function encode($data);
    public function decode($data);
    public function prefix(): string;
}
