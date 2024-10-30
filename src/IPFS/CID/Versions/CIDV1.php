<?php

namespace Atproto\IPFS\CID\Versions;

use Atproto\IPFS\CID\CIDVersion;
use Atproto\IPFS\MultiFormats\MultiCodec;
use Atproto\IPFS\MultiFormats\MultiHash;

class CIDV1 extends CIDVersion
{
    public function generate(): string
    {
        $version = $this->versionMultiCodecBin();
        $type = $this->typeMultiCodecBin();
        $contentMultiHash = $this->contentMultiHash();

        return sprintf(
            "%s%s%s",
            $version,
            $type,
            $contentMultiHash
        );
    }

    private function versionMultiCodecBin(): string
    {
        return hex2bin($this->versionMultiCodec()->value);
    }

    private function typeMultiCodecBin(): string
    {
        return hex2bin($this->cid->typeMultiCodec()->value);
    }

    private function contentMultiHash(): string
    {
        return MultiHash::generate('sha2-256', $this->cid->target());
    }

    private function versionMultiCodec(): MultiCodec
    {
        return MultiCodec::get('cidv1');
    }
}
