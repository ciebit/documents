<?php
namespace Ciebit\Documents;

class Document
{
    private $file; #File
    private $label; #Label
    private $status; #Status

    public function __construct(
        File $file,
        Label $label,
        Status $status
    )
    {
        $this->file = $file;
        $this->label = $label;
        $this->status = $status;
    }

    public function getFile(): File
    {
        return $this->file;
    }

    public function getLabel(): Label
    {
        return $this->label;
    }
}
