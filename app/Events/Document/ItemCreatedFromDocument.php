<?php

namespace App\Events\Document;

use App\Abstracts\Event;
use App\Models\Common\Item;
use App\Models\Document\Document;

/**
 * An item was created for a line of a document, i.e. written on the line and not picked from the list, when the
 * document is saved or imported. Fired once the item is created and before the line is, so an app can set the new
 * item up as the line has it.
 */
class ItemCreatedFromDocument extends Event
{
    public $item;

    public $document;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param  Item  $item  the item created for the line
     * @param  Document  $document  the document the line is on
     * @param  array  $request  the line as it was given, its category included
     */
    public function __construct(Item $item, Document $document, array $request)
    {
        $this->item = $item;
        $this->document = $document;
        $this->request = $request;
    }
}
