<?php

namespace Tests\Unit\Lexicons\App\Bsky\RichText;

use Atproto\Lexicons\App\Bsky\RichText\FeatureAbstract;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Tests\Supports\Reflection;

class FeatureAbstractTest extends TestCase
{
    use Reflection;

    /**
     * @throws ReflectionException
     */
    public function testConstructorAssignsReferenceAndLabel()
    {
        $mock = $this->getMockBuilder(FeatureAbstract::class)
            ->setConstructorArgs(['reference', 'label'])
            ->getMockForAbstractClass();

        $reference = $this->getPropertyValue('reference', $mock);
        $label = $this->getPropertyValue('label', $mock);

        $this->assertSame('reference', $reference);
        $this->assertSame('label', $label);
    }

    public function testJsonSerializeReturnsCorrectArray()
    {
        $mock = $this->getMockBuilder(FeatureAbstract::class)
            ->setConstructorArgs(['reference', 'label'])
            ->onlyMethods(['jsonSerialize'])
            ->getMockForAbstractClass();

        $schema = ['key' => 'value'];

        $mock->expects($this->once())
            ->method('jsonSerialize')
            ->willReturn($schema);

        $this->assertSame(
            $schema,
            $mock->jsonSerialize()
        );
    }
}
