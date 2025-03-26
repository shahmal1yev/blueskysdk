<?php

namespace Tests\Feature\FieldTypes;

use Atproto\FieldTypes\FieldType;
use Atproto\Contracts\LexiconContract;
use PHPUnit\Framework\TestCase;

class FieldTypeTest extends TestCase
{
    public function test_full_casting_flow()
    {
        $input = [
            'embed' => [
                '$type' => 'com.example.fake#view',
                'foo' => 'bar'
            ],
            'items' => [
                ['foo' => 'baz'],
                ['foo' => 'qux']
            ],
        ];

        $fields = [
            'embed' => FieldType::union([
                FakeVariant::class,
            ])->description('Union field'),

            'items' => FieldType::array(
                FieldType::object(FakeVariant::class)->nullable(false)
            )->minLength(1)->maxLength(10),
        ];

        foreach ($fields as $key => $castable) {
            $result = $castable->handler()->handle(
                $input[$key],
                $castable->definition()
            );

            if ($key === 'embed') {
                $this->assertInstanceOf(FakeVariant::class, $result);
                $this->assertEquals('bar', $result->data()['foo']);
            }

            if ($key === 'items') {
                $this->assertCount(2, $result);
                $this->assertInstanceOf(FakeVariant::class, $result[0]);
                $this->assertEquals('baz', $result[0]->data()['foo']);
            }
        }
    }

    public function test_nullable_object_casts_to_null()
    {
        $input = ['data' => null];

        $fields = [
            'data' => FieldType::object(FakeVariant::class)->nullable(true),
        ];

        $result = $fields['data']->handler()->handle(
            $input['data'],
            $fields['data']->definition()
        );

        $this->assertNull($result);
    }

    public function test_non_nullable_object_throws_on_null()
    {
        $this->expectException(\TypeError::class);

        $input = ['data' => null];

        $fields = [
            'data' => FieldType::object(FakeVariant::class)->nullable(false),
        ];

        $fields['data']->handler()->handle(
            $input['data'],
            $fields['data']->definition()
        );
    }

    public function test_closed_union_rejects_unknown_type()
    {
        $this->expectException(\InvalidArgumentException::class);

        $input = [
            '$type' => 'com.unknown#bad',
            'foo' => 'bar',
        ];

        $field = FieldType::union([
            FakeVariant::class
        ])->closed(true);

        $field->handler()->handle($input, $field->definition());
    }

    public function test_object_with_properties_casts_nested_objects()
    {
        $input = [
            'outer' => [
                'inner' => [
                    'foo' => 'bar'
                ]
            ]
        ];

        $inner = FieldType::object(FakeVariant::class);
        $outer = FieldType::object(FakeVariant::class)->properties([
            'inner' => $inner
        ]);

        $result = $outer->handler()->handle($input['outer'], $outer->definition());

        $this->assertInstanceOf(FakeVariant::class, $result);
        $this->assertEquals(['inner' => ['foo' => 'bar']], $result->data());
    }

    public function test_description_reflected_in_serialized_metadata()
    {
        $field = FieldType::object(FakeVariant::class)
            ->description('This is a test object');

        $serialized = json_encode($field->definition());

        $this->assertStringContainsString('"description":"This is a test object"', $serialized);
    }

    public function test_open_union_allows_unknown_type_but_fails_without_handler()
    {
        $this->expectException(\InvalidArgumentException::class);

        $input = [
            '$type' => 'unknown.type#blah',
            'foo' => 'baz',
        ];

        $union = FieldType::union([
            FakeVariant::class
        ])->closed(false);

        $union->handler()->handle($input, $union->definition());
    }

    public function test_nullable_default_is_false_and_throws_on_null()
    {
        $this->expectException(\TypeError::class);

        $input = ['data' => null];

        $field = FieldType::object(FakeVariant::class);

        $field->handler()->handle($input['data'], $field->definition());
    }

    public function test_array_of_union_casts_properly()
    {
        $input = [
            ['foo' => 'bar', '$type' => FakeVariant::nsid()],
            ['bar' => 'baz', '$type' => AnotherFakeVariant::nsid()],
        ];

        $field = FieldType::array(
            FieldType::union([
                FakeVariant::class,
                AnotherFakeVariant::class
            ])
        );

        $result = $field->handler()->handle($input, $field->definition());

        $this->assertCount(2, $result);
        $this->assertInstanceOf(FakeVariant::class, $result[0]);
        $this->assertInstanceOf(AnotherFakeVariant::class, $result[1]);
    }

    public function test_union_of_object_and_array_casts()
    {
        $input = [
            '$type' => 'com.example.arr#view',
            'data' => [
                ['foo' => 'a'],
                ['foo' => 'b']
            ]
        ];

        $field = FieldType::union([
            FakeArrayWrapper::class,
            FakeVariant::class,
        ]);

        $result = $field->handler()->handle($input, $field->definition());

        $this->assertInstanceOf(FakeArrayWrapper::class, $result);
        $this->assertEquals('a', $result->data()['data'][0]['foo']);
    }

    public function test_metadata_serialization_is_correct()
    {
        $field = FieldType::object(FakeVariant::class)
            ->nullable(true)
            ->description('Some object field')
            ->properties([
                'foo' => FieldType::object(FakeVariant::class)
            ]);

        $definition = $field->definition();

        $meta = $definition->metadata();

        $this->assertEquals('object', $meta['type']);
        $this->assertTrue($meta['nullable']);
        $this->assertEquals('Some object field', $meta['description']);
        $this->assertArrayHasKey('properties', $meta);
        $this->assertIsArray($meta['properties']);
    }
}

class FakeVariant implements LexiconContract
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function data(): array
    {
        return $this->data;
    }

    public static function nsid(): string
    {
        return 'com.example.fake#view';
    }

    public function jsonSerialize() {}

    public function __toString(): string
    {
        return json_encode($this->data);
    }
}

class AnotherFakeVariant implements LexiconContract {
    private array $data;
    public function __construct(array $data) {$this->data = $data;}
    public function data(): array { return $this->data; }
    public static function nsid(): string { return 'com.example.other#view'; }
    public function jsonSerialize() {}
    public function __toString(): string { return json_encode($this->data); }
}

class FakeArrayWrapper implements LexiconContract {
    private array $data;
    public function __construct(array $data) {$this->data = $data;}
    public function data(): array { return $this->data; }
    public static function nsid(): string { return 'com.example.arr#view'; }
    public function jsonSerialize() {}
    public function __toString(): string { return json_encode($this->data); }
}

