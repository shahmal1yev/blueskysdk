<?php

namespace Tests\Unit\Lexicons\Com\Atproto\Server;

use Atproto\Client;
use PHPUnit\Framework\TestCase;

class RefreshSessionTest extends TestCase
{
    public function testTokenCanSetNewToken()
    {
        $expectedToken = 'token';

        $builder = (new Client())->com()->atproto()->server()->refreshSession()->forge()
            ->token($expectedToken);

        $this->assertSame($expectedToken, $builder->token());
    }

    public function testItWorksWithoutAuth(): void
    {
        $this->expectNotToPerformAssertions();

        (new Client())->com()->atproto()->server()->refreshSession()->forge();
    }

    public function testForgeCanSetNewToken(): void
    {
        $expectedToken = 'token';
        $actualToken = (new Client())->com()->atproto()->server()->refreshSession()
            ->forge($expectedToken)
            ->token();

        $this->assertSame($expectedToken, $actualToken);
    }

    public function testTokenCanUpdateTokenAfterForge(): void
    {
        $expectedToken = 'token';
        $actualToken = (new Client())->com()->atproto()->server()->refreshSession()
            ->forge('another token')
            ->token($expectedToken)
            ->token();

        $this->assertSame($expectedToken, $actualToken);
    }
}
