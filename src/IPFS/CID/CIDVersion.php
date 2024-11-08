<?php

namespace Atproto\IPFS\CID;

abstract class CIDVersion
{
    protected CID $cid;

    public function setCid(CID $cid): void
    {
        $this->cid = $cid;
    }

    abstract public function generate(): string;
}
