<?php
namespace Ciebit\Documents\Builders;

use Ciebit\Documents\Document;

interface Builder
{
    public function build(): Document;
}
