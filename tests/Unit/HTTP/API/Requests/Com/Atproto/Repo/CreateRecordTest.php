<?php

namespace Tests\Unit\HTTP\API\Requests\Com\Atproto\Repo;

use Atproto\Client;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\HTTP\API\Requests\Com\Atproto\Repo\CreateRecord;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class CreateRecordTest extends TestCase
{
    private CreateRecord $createRecord;
    private Generator $faker;

    protected function setUp(): void
    {
        $this->createRecord = new CreateRecord($this->createMock(Client::class));
        $this->faker = Factory::create();
    }

    public function testRepo()
    {
        $this->assertNull($this->createRecord->repo());

        $expected = $this->faker->word;
        $this->createRecord->repo($expected);
        $this->assertEquals($expected, $this->createRecord->repo());
    }

    public function testCollection()
    {
        $this->assertNull($this->createRecord->collection());

        $expected = $this->faker->word;
        $this->createRecord->collection($expected);
        $this->assertEquals($expected, $this->createRecord->collection());
    }

    public function testRkey()
    {
        $this->assertNull($this->createRecord->rkey());

        $expected = $this->faker->word;
        $this->createRecord->rkey($expected);
        $this->assertEquals($expected, $this->createRecord->rkey());
    }

    public function testValidate()
    {
        $this->assertNull($this->createRecord->validate());

        $this->createRecord->validate(true);
        $this->assertTrue($this->createRecord->validate());
    }

    public function testRecord()
    {
        $this->assertNull($this->createRecord->record());

        $record = (object)['key' => 'value'];
        $this->createRecord->record($record);
        $this->assertEquals($record, $this->createRecord->record());
    }

    public function testSwapCommit()
    {
        $this->assertNull($this->createRecord->swapCommit());

        $expected = $this->faker->word;
        $this->createRecord->swapCommit($expected);
        $this->assertEquals($expected, $this->createRecord->swapCommit());
    }

    /**
     * @throws MissingFieldProvidedException
     */
    public function testBuildWithAllRequiredFields()
    {
        $this->createRecord->repo($this->faker->word)
            ->collection($this->faker->word)
            ->record((object)['key' => 'value']);

        $result = $this->createRecord->build();

        $this->assertInstanceOf(CreateRecord::class, $result);
    }

    public function testBuildWithMissingRequiredFields()
    {
        $this->expectException(MissingFieldProvidedException::class);
        $this->expectExceptionMessage("record");

        $this->createRecord->repo($this->faker->word)
            ->collection($this->faker->word);

        $this->createRecord->build();
    }

    public function testChaining()
    {
        $result = $this->createRecord->repo($this->faker->word)
            ->collection($this->faker->word)
            ->rkey($this->faker->word)
            ->validate(true)
            ->record((object)['key' => 'value'])
            ->swapCommit($this->faker->word);

        $this->assertInstanceOf(CreateRecord::class, $result);
    }
}
