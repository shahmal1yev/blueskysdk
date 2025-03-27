<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed\Record;

use Atproto\Lexicons\App\Bsky\Embed\Record\View;
use Atproto\Lexicons\App\Bsky\Embed\Record\ViewRecord;
use Atproto\Lexicons\App\Bsky\Graph\Defs\ListView;
use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    public function test_nsid_is_correct(): void
    {
        $this->assertSame(
            'app.bsky.embed.record#view',
            View::nsid()
        );

        $this->assertSame(
            'app.bsky.embed.record#view',
            (new View([]))->nsid()
        );
    }

    public function test_record_casts_to_view_record(): void
    {
        $data = [
            'record' => [
                '$type' => 'app.bsky.embed.record#viewRecord',
                'data' => 'record-data'
            ]
        ];

        $view = new View($data);

        $this->assertInstanceOf(ViewRecord::class, $view->record());
    }

    public function test_record_casts_to_list_view(): void
    {
        $data = [
            'record' => [
                '$type' => 'app.bsky.graph.defs#listView',
                'data' => 'list-data'
            ]
        ];

        $view = new View($data);

        $this->assertInstanceOf(ListView::class, $view->record());
    }

    public function test_record_cast_throws_on_invalid_type(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $data = [
            'record' => [
                '$type' => 'unknown.namespace#bogusType',
                'value' => 'fail'
            ]
        ];

        (new View($data))->record();
    }
}
