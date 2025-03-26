<?php

namespace Tests\Unit\FieldTypes\Definitions;

use Atproto\FieldTypes\Definitions\AbstractDefinition;
use PHPUnit\Framework\TestCase;

class AbstractDefinitionTest extends TestCase
{
    public function test_metadata_returns_all_set_meta_fields()
    {
        $definition = new class extends AbstractDefinition {
            public function set(string $key, $value): void
            {
                $this->meta[$key] = $value;
            }
        };

        $definition->set('type', 'object');
        $definition->set('description', 'Test object');

        $this->assertEquals([
            'type' => 'object',
            'description' => 'Test object',
        ], $definition->metadata());
    }

    public function test_json_serialize_returns_filtered_metadata()
    {
        $definition = new class extends AbstractDefinition {
            public function set(string $key, $value): void
            {
                $this->meta[$key] = $value;
            }
        };

        $definition->set('type', 'array');
        $definition->set('description', null); // should be filtered

        $this->assertEquals([
            'type' => 'array'
        ], $definition->jsonSerialize());
    }

    public function test_type_returns_correct_type()
    {
        $definition = new class extends AbstractDefinition {
            public function __construct() {
                $this->meta['type'] = 'union';
            }
        };

        $this->assertEquals('union', $definition->type());
    }

    public function test_description_set_and_get_behavior()
    {
        $definition = new class extends AbstractDefinition {};

        $this->assertNull($definition->description(null));

        $definition->description('A sample description');

        $this->assertEquals('A sample description', $definition->description(null));
    }
}
