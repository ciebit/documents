<?php
declare(strict_types=1);
namespace Ciebit\Documents;

use ArrayIterator;
use ArrayObject;

class Collection
{
    private $documents; #: ArrayObject

    public function __construct()
    {
        $this->documents = new ArrayObject;
    }

    public function add(Document $document): self
    {
        $this->documents->append($document);
        return $this;
    }

    public function getById(int $id): ?Document
    {
        $iterator = $this->getIterator();
        foreach ($iterator as $document) {
            if ($document->getId() == $id) {
                return $document;
            }
        }
        return null;
    }

    public function getArrayObject(): ArrayObject
    {
        return clone $this->documents;
    }

    public function getIterator(): ArrayIterator
    {
        return $this->documents->getIterator();
    }
}
