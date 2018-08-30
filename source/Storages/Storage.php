<?php
namespace Ciebit\Documents\Storages;

use Ciebit\Documents\Collection;
use Ciebit\Documents\Document;
use Ciebit\Documents\Status;

interface Storage
{    
    public function addFilterById(int $id, string $operator = '='): self;
    
    public function addFilterByStatus(Status $status, string $operator = '='): self;
    
    public function addFilterByTitle(string $title, string $operator = '='): self;
    
    public function addFilterByUri(string $uri, string $operator = '='): self;
    
    public function get(): ?Document;
    
    public function getAll(): Collection;
    
    public function setStartingLine(int $lineInit): self;
    
    public function setTotalLines(int $total): self;
}
