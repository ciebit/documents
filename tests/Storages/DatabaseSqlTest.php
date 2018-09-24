<?php
namespace Ciebit\Documents\Tests\Storages;

use Ciebit\Documents\Collection;
use Ciebit\Documents\Status;
use Ciebit\Documents\Document;
use Ciebit\Documents\Storages\Database\Sql as DatabaseSql;
use Ciebit\Documents\Tests\Connection;

class DatabaseSqlTest extends Connection
{
    public function getDatabase(): DatabaseSql
    {
        return new DatabaseSql($this->getPdo());
    }

    public function testGet(): void
    {
        $database = $this->getDatabase();
        $document = $database->get();
        $this->assertInstanceOf(Document::class, $document);
    }

    public function testGetAll(): void
    {
        $database = $this->getDatabase();
        $documentsCollection = $database->getAll();
        $this->assertInstanceOf(Collection::class, $documentsCollection);
        $this->assertCount(4, $documentsCollection->getIterator());
    }

    public function testGetAllFilterById(): void
    {
        $id = 3;
        $database = $this->getDatabase();
        $database->addFilterById($id+0);
        $documentsCollection = $database->getAll();
        $this->assertCount(1, $documentsCollection->getIterator());
        $this->assertEquals($id, $documentsCollection->getArrayObject()->offsetGet(0)->getId());
    }

    public function testGetAllFilterByStatus(): void
    {
        $database = $this->getDatabase();
        $database->addFilterByStatus(Status::ACTIVE());
        $documentsCollection = $database->getAll();
        $this->assertCount(1, $documentsCollection->getIterator());
        $this->assertEquals(Status::ACTIVE(), $documentsCollection->getArrayObject()->offsetGet(0)->getStatus());
    }

    public function testGetFilterById(): void
    {
        $id = 2;
        $database = $this->getDatabase();
        $database->addFilterById($id+0);
        $document = $database->get();
        $this->assertEquals($id, $document->getId());
    }

    public function testGetFilterByStatus(): void
    {
        $database = $this->getDatabase();
        $database->addFilterByStatus(Status::ACTIVE());
        $document = $database->get();
        $this->assertEquals(Status::ACTIVE(), $document->getStatus());
    }

    public function testGetFilterByTitle(): void
    {
        $database = $this->getDatabase();
        $database->addFilterByTitle('Decreto 987/2018');
        $document = $database->get();
        $this->assertEquals(3, $document->getId());

        $database = $this->getDatabase();
        $database->addFilterByTitle('%Bimestre de 2018', 'LIKE');
        $document = $database->get();
        $this->assertEquals(1, $document->getId());
    }

    public function testGetFilterByUri(): void
    {
        $database = $this->getDatabase();
        $database->addFilterByUri('rgf-2018-2-semestre.pdf');
        $document = $database->get();
        $this->assertEquals(2, $document->getId());
    }
}
