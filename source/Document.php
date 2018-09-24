<?php
namespace Ciebit\Documents;

use Ciebit\Labels\Label;
use Ciebit\Files\File;

class Document
{
    private $id; #int
    private $file; #File
    private $label; #Label
    private $status; #Status

    public function __construct(
        File $file,
        Label $label,
        Status $status
    )
    {
        $this->id = 0;
        $this->file = $file;
        $this->label = $label;
        $this->status = $status;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFile(): File
    {
        return $this->file;
    }

    public function getLabel(): Label
    {
        return $this->label;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
}
