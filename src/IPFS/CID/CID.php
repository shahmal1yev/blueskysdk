<?php

namespace Atproto\IPFS\CID;

use Atproto\Contracts\EncoderContract;
use Atproto\Contracts\Stringable;
use Atproto\IPFS\CID\Versions\CIDV1;
use Atproto\MultiFormats\MultiBase\MultiBase;
use Atproto\MultiFormats\MultiCodec;

class CID implements Stringable
{
    private string $target;
    private CIDVersion $version;
    private MultiCodec $typeMultiCodec;
    private EncoderContract $encoder;

    public function __construct(MultiCodec $typeMultiCodec, MultiBase $encoderMultiBase, string $target)
    {
        $this->encoder = $encoderMultiBase->value;
        $this->typeMultiCodec = $typeMultiCodec;
        $this->target = $target;
        $this->version = new CIDV1();
        $this->version->setCid($this);
    }

    public function target(): string
    {
        return $this->target;
    }

    public function typeMultiCodec(): MultiCodec
    {
        return $this->typeMultiCodec;
    }

    public function version(CIDVersion $version = null)
    {
        if (is_null($version)) {
            return $this->version;
        }

        $this->version = $version;
        $this->version->setCid($this);

        return $this;
    }

    public function generate(): string
    {
        return $this->version->generate();
    }

    public function __toString(): string
    {
        return $this->encoder->encode($this->generate());
    }
}
