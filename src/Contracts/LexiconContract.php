<?php

namespace Atproto\Contracts;

interface LexiconContract extends SerializableContract
{
    public function nsid(): string;
}
