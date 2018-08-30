<?php
declare(strict_types=1);

namespace Ciebit\Documents\Builders;

use Ciebit\Documents\Document;
use Ciebit\Documents\Status;
use DateTime;

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
            ! is_array($this->data)
        ) {
            throw new Exception('ciebit.documents.builders.invalid', 3);
        }
        $file = (new StoryBuilder)->setData($this->data['story'])->build();
        $status = $this->data['status'] ? new Status((int) $this->data['status']) : Status::DRAFT();

        $document = new Document(
            $file,
            $status
        );
        
        $this->data['id'] && $document->setId((int) $this->data['id']);
        $this->data['image'] && $document->setImage(
           (new ImageBuilder)->setData($this->data['image'])->build()
        );
        

        return $document;
    }
}
