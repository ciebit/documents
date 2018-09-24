<?php
declare(strict_types=1);
namespace Ciebit\Documents\Storages\Database;

use Ciebit\Documents\Collection;
use Ciebit\Documents\Builders\FromArray as Builder;
use Ciebit\Documents\Document;
use Ciebit\Documents\Status;
use Ciebit\Documents\Storages\Storage;
use Exception;
use PDO;

class Sql extends SqlFilters implements Database
{
    private $pdo; #PDO
    private $tableGet; #string
    private $tableSave; #string

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->tableGet = 'cb_documents_complete';
        $this->tableSave = 'cb_documents';
    }

    public function addFilterById(int $id, string $operator = '='): Storage
    {
        $key = 'id';
        $sql = "`documents`.`id` $operator :{$key}";
        $this->addfilter($key, $sql, PDO::PARAM_INT, $id);
        return $this;
    }

    public function addFilterByStatus(Status $status, string $operator = '='): Storage
    {
        $key = 'status';
        $sql = "`documents`.`status` {$operator} :{$key}";
        $this->addFilter($key, $sql, PDO::PARAM_INT, $status->getValue());
        return $this;
    }

    public function addFilterByTitle(string $title, string $operator = '='): Storage
    {
        $key = 'title';
        $sql = "`documents`.`file_name` {$operator} :{$key}";
        $this->addFilter($key, $sql, PDO::PARAM_STR, $title);
        return $this;
    }

    public function addFilterByUri(string $uri, string $operator = '='): Storage
    {
        $key = 'uri';
        $sql = "`documents`.`file_uri` {$operator} :{$key}";
        $this->addFilter($key, $sql, PDO::PARAM_STR, $uri);
        return $this;
    }

    public function get(): ?Document
    {
        $statement = $this->pdo->prepare("
            SELECT
            {$this->getFields()}
            FROM {$this->tableGet} as `documents`
            WHERE {$this->generateSqlFilters()}
            LIMIT 1
        ");
        $this->bind($statement);
        if ($statement->execute() === false) {
            throw new Exception('ciebit.documents.storages.database.get_error', 2);
        }
        $documentData = $statement->fetch(PDO::FETCH_ASSOC);
        if ($documentData == false) {
            return null;
        }
        
        $standarsizedData = $this->standardizeData($documentData);
        return (new Builder)->setData($standarsizedData)->build();
    }

    private function standardizeData(array $data): array
    {
        return [
            'id' => $data['id'],
            'file' => [
                'id' => $data['file_id'],
                'name' => $data['file_name'],
                'description' => $data['file_description'],
                'uri' => $data['file_uri'],
                'extension' => $data['file_extension'],
                'size' => $data['file_size'],
                'mimetype' => $data['file_mimetype'],
                'date_hour' => $data['file_date_hour'],
                'metadata' => $data['file_metadata'],
                'status' => $data['file_status']
            ],
            'label' => [
                'id' => $data['label_id'],
                'title' => $data['label_title'],
                'ascendants_id' => $data['label_ascendants_id'],
                'uri' => $data['label_uri'],
                'status' => $data['label_status']
            ],
            'status' => $data['status']
        ];
    }

    public function getAll(): Collection
    {
        $statement = $this->pdo->prepare("
            SELECT SQL_CALC_FOUND_ROWS
            {$this->getFields()}
            FROM {$this->tableGet} as `documents`
            WHERE {$this->generateSqlFilters()}
            {$this->generateSqlLimit()}
        ");
        $this->bind($statement);
        if ($statement->execute() === false) {
            throw new Exception('ciebit.stories.storages.database.get_error', 2);
        }
        $collection = new Collection;
        $builder = new Builder;
        while ($document = $statement->fetch(PDO::FETCH_ASSOC)) {
            $standarsizedData = $this->standardizeData($document);
            $builder->setData($standarsizedData);
            $collection->add(
                $builder->build()
            );
        }
        return $collection;
    }

    private function getFields(): string
    {
        return '
            `documents`.`id`,
            `documents`.`status`,
            `documents`.`file_id`,
            `documents`.`file_name`,
            `documents`.`file_description`,
            `documents`.`file_uri`,
            `documents`.`file_extension`,
            `documents`.`file_size`,
            `documents`.`file_mimetype`,
            `documents`.`file_date_hour`,
            `documents`.`file_metadata`,
            `documents`.`file_status`,
            `documents`.`label_id`,
            `documents`.`label_title`,
            `documents`.`label_ascendants_id`,
            `documents`.`label_uri`,
            `documents`.`label_status`
        ';
    }

    public function getTotalRows(): int
    {
        return $this->pdo->query('SELECT FOUND_ROWS()')->fetchColumn();
    }

    public function setStartingLine(int $lineInit): Storage
    {
        parent::setOffset($lineInit);
        return $this;
    }

    public function setTableGet(string $name): Database
    {
        $this->tableGet = $name;
        return $this;
    }

    public function setTableSave(string $name): Database
    {
        $this->tableSave = $name;
        return $this;
    }

    public function setTotalLines(int $total): Storage
    {
        parent::setLimit($total);
        return $this;
    }
}
