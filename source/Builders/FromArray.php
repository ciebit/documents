<?php
declare(strict_types=1);

namespace Ciebit\Documents\Builders;

use Ciebit\Documents\Document;
use Ciebit\Documents\Status;
use Ciebit\Labels\Status as LabelStatus;
use Ciebit\Files\Builders\Context as FileBuilder;
use Ciebit\Labels\Label;
use DateTime;
use Exception;

class FromArray implements Builder
{
    private $data; #:array

    public function setData(array $data): Builder
    {
        $this->data = $data;
        return $this;
    }

    public function build(): Document
    {
        if (
            ! is_array($this->data) OR
            ! isset($this->data['file']) OR
            ! isset($this->data['label'])
        ) {
            throw new Exception('ciebit.documents.builders.invalid', 3);
        }
        $label_data = $this->data['label'];
        $file = (new FileBuilder)->setData($this->data['file'])->build();
        $label = (new Label(
            $label_data['title'],
            $label_data['uri'],
            new LabelStatus((int) $label_data['status'])
        ))
        ->setAscendantsId(array($label_data['ascendants_id']))
        ->setId($label_data['id']);
        $status = $this->data['status'] ? new Status((int) $this->data['status']) : Status::DRAFT();

        $document = new Document(
            $file,
            $label,
            new Status((int) $this->data['status'])
        );
        
        $this->data['id'] && $document->setId((int) $this->data['id']);        

        return $document;
    }
}
