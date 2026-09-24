<?php

namespace Tests\Feature\Document;

use App\Jobs\Document\UpdateDocument;
use App\Models\Document\Document;
use App\Models\Document\DocumentItem;
use App\Models\Document\DocumentItemTax;
use App\Models\Document\DocumentTotal;
use Illuminate\Database\Eloquent\Collection;
use Tests\Feature\FeatureTestCase;

/**
 * Updating a document's lines replaces the stored ones, whatever the model
 * instance has loaded before.
 */
class UpdateDocumentTest extends FeatureTestCase
{
    public function testItReplacesTheStoredLinesWhenTheLoadedOnesAreStale(): void
    {
        $invoice = Document::factory()->invoice()->draft()->create();

        // The factory's own update read these before creating them; unless a listener refreshes the model, it keeps the empty lists
        foreach (['items', 'item_taxes', 'totals'] as $relation) {
            $invoice->setRelation($relation, new Collection());
        }

        $request = Document::factory()->invoice()->items()->raw();
        $request['contact_id'] = $invoice->contact_id;
        $request['items'][0]['name'] = 'Replacement';

        $this->dispatch(new UpdateDocument($invoice, $request));

        $this->assertSame(['Replacement'], DocumentItem::where('document_id', $invoice->id)->pluck('name')->all());
        $this->assertSame(1, DocumentItemTax::where('document_id', $invoice->id)->count());
        $this->assertSame(1, DocumentTotal::where('document_id', $invoice->id)->where('code', 'total')->count());
    }
}
