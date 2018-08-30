<?php
namespace Ciebit\Documents\Storages\Database;

use Ciebit\Documents\Document;
use Ciebit\Documents\Collection;
use Ciebit\Documents\Storages\Storage;

interface Database extends Storage
{
    public function setTableGet(string $name): self;
    public function setTableSave(string $name): self;
}
