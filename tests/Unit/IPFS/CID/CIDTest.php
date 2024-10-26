<?php

namespace Tests\Unit\IPFS\CID;

use Atproto\IPFS\CID\CID;
use Atproto\IPFS\CID\CIDVersion;
use Atproto\MultiFormats\MultiBase\Encoders\Base32Encoder;
use Atproto\MultiFormats\MultiBase\MultiBase;
use Atproto\MultiFormats\MultiCodec;
use PHPUnit\Framework\TestCase;
use Tests\Supports\Reflection;

class CIDTest extends TestCase
{
    use Reflection;

    public function testGenerate(): void
    {
        $cid = new CID(MultiCodec::get('raw'), MultiBase::get('base32'), 'content');

        $expectedHex = '01551220ed7002b439e9ac845f22357d822bac1444730fbdb6016d3ec9432297b9ec9f73';

        $actualBinary = $cid->generate();
        $expectedBinary = hex2bin($expectedHex);

        $actualHex = bin2hex($actualBinary);

        $this->assertSame($expectedHex, $actualHex);

        $actualLen = strlen($actualHex);
        $expectedLen = strlen($expectedHex);

        $this->assertSame($expectedLen, $actualLen);

        $actualBase32 = substr($cid->__toString(), 1);
        $expectedBase32 = substr((new Base32Encoder())->encode($expectedBinary), 1);

        $this->assertSame($expectedBase32, $actualBase32);
    }

    public function testVersionChanging(): void
    {
        $mockVersion = new class () extends CIDVersion {
            public function generate(): string
            {
                return '';
            }
        };

        $cid = new CID(MultiCodec::get('raw'), MultiBase::get('base32'), '');

        $cid->version($mockVersion);

        $this->assertSame($mockVersion, $cid->version());
        $this->assertSame($cid, $this->getPropertyValue('cid', $mockVersion));
    }
}
