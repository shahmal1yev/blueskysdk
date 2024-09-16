<?php

namespace Tests\Unit\HTTP\API\Requests\Com\Atproto\Repo;

use Atproto\Exceptions\Http\MissingProvidedFieldException;
use Atproto\HTTP\API\Requests\Com\Atproto\Repo\CreateRecord;
use PHPUnit\Framework\TestCase;

class CreateRecordTest extends TestCase
{
    private CreateRecord $createRecord;

    protected function setUp(): void
    {
        $this->createRecord = new CreateRecord();
    }

    public function testConstructor()
    {
        $this->assertEquals('/com.atproto.repo.createRecord', $this->createRecord->path());
    }

    public function testRepo()
    {
        $this->assertNull($this->createRecord->repo());

        $this->createRecord->repo('test-repo');
        $this->assertEquals('test-repo', $this->createRecord->repo());
    }

    public function testCollection()
    {
        $this->assertNull($this->createRecord->collection());

        $this->createRecord->collection('test-collection');
        $this->assertEquals('test-collection', $this->createRecord->collection());
    }

    public function testRkey()
    {
        $this->assertNull($this->createRecord->rkey());

        $this->createRecord->rkey('test-rkey');
        $this->assertEquals('test-rkey', $this->createRecord->rkey());
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

        $this->createRecord->swapCommit('test-swap-commit');
        $this->assertEquals('test-swap-commit', $this->createRecord->swapCommit());
    }

    /**
     * @throws MissingProvidedFieldException
     */
    public function testBuildWithAllRequiredFields()
    {
        $this->createRecord->repo('test-repo')
            ->collection('test-collection')
            ->record((object)['key' => 'value']);

        $result = $this->createRecord->build();

        $this->assertInstanceOf(CreateRecord::class, $result);
    }

    public function testBuildWithMissingRequiredFields()
    {
        $this->expectException(MissingProvidedFieldException::class);
        $this->expectExceptionMessage("record");

        $this->createRecord->repo('test-repo')
            ->collection('test-collection');

        $this->createRecord->build();
    }

    public function testChaining()
    {
        $result = $this->createRecord->repo('test-repo')
            ->collection('test-collection')
            ->rkey('test-rkey')
            ->validate(true)
            ->record((object)['key' => 'value'])
            ->swapCommit('test-swap-commit');

        $this->assertInstanceOf(CreateRecord::class, $result);
    }
}