<?php

namespace Tests\Unit\Lexicons\Com\Atproto\Repo;

use Atproto\Client;
use Atproto\Contracts\Lexicons\App\Bsky\Feed\PostBuilderContract;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Lexicons\Com\Atproto\Repo\CreateRecord;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class CreateRecordTest extends TestCase
{
    private CreateRecord $createRecord;
    private Generator $faker;

    protected function setUp(): void
    {
        $this->createRecord = new CreateRecord();
        $this->faker = Factory::create();
    }

    public function testRepo()
    {
        $this->assertNull($this->createRecord->repo());

        $expected = $this->faker->word;
        $req = $this->createRecord->repo($expected);
        $this->assertEquals($expected, $req->repo());
    }

    public function testCollection()
    {
        $this->assertNull($this->createRecord->collection());

        $expected = $this->faker->word;
        $request = $this->createRecord->collection($expected);
        $this->assertEquals($expected, $request->collection());
    }

    public function testRkey()
    {
        $this->assertNull($this->createRecord->rkey());

        $expected = $this->faker->word;
        $request = $this->createRecord->rkey($expected);
        $this->assertEquals($expected, $request->rkey());
    }

    public function testValidate()
    {
        $this->assertNull($this->createRecord->validate());

        $request = $this->createRecord->validate(true);
        $this->assertTrue($request->validate());
    }

    public function testRecord()
    {
        $this->assertNull($this->createRecord->record());

        $record = $this->createMock(PostBuilderContract::class);
        $request = $this->createRecord->record($record);
        $this->assertEquals($record, $request->record());
    }

    public function testSwapCommit()
    {
        $this->assertNull($this->createRecord->swapCommit());

        $expected = $this->faker->word;
        $request = $this->createRecord->swapCommit($expected);
        $this->assertEquals($expected, $request->swapCommit());
    }

    public function testChaining()
    {
        $result = $this->createRecord->repo($this->faker->word)
            ->collection($this->faker->word)
            ->rkey($this->faker->word)
            ->validate(true)
            ->record($this->createMock(PostBuilderContract::class))
            ->swapCommit($this->faker->word);

        $this->assertInstanceOf(CreateRecord::class, $result);
    }
}
