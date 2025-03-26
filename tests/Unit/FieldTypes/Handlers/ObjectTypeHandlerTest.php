<?php

namespace Tests\Unit\FieldTypes\Handlers;

use Atproto\FieldTypes\Definitions\ObjectTypeDefinition;
use Atproto\FieldTypes\Handlers\ObjectTypeHandler;
use PHPUnit\Framework\TestCase;

class ObjectTypeHandlerTest extends TestCase
{
    public function test_it_instantiates_target_class()
    {
        $data = ['name' => 'Eldar'];

        $definition = new ObjectTypeDefinition(FakeObject::class);
        $handler = new ObjectTypeHandler();

        $result = $handler->handle($data, $definition);

        $this->assertInstanceOf(FakeObject::class, $result);
        $this->assertEquals($data, $result->getData());
    }

    public function test_it_throws_if_class_does_not_exist()
    {
        $this->expectException(\InvalidArgumentException::class);

        $definition = new ObjectTypeDefinition('NonExistent\\Class\\Here');
        $handler = new ObjectTypeHandler();

        $handler->handle([], $definition);
    }
}

class FakeObject
{
    private array $data;
    public function __construct(array $data) { $this->data = $data; }
    public function getData(): array { return $this->data; }
}
