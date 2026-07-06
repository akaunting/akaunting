<?php

namespace App\Events\Document;

use App\Abstracts\Event;

class DocumentResourceShowing extends Event
{
    public $document;

    public $resources = [];

    /**
     * Create a new event instance.
     *
     * @param $document
     */
    public function __construct($document)
    {
        $this->document = $document;
    }

    /**
     * Add extra resources to the document API response.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addResource(string $key, $value): void
    {
        $this->resources[$key] = $value;
    }
}
