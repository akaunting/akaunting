<?php

namespace Tests\Feature\Document;

use App\Http\Requests\Document\DocumentItem;
use App\Models\Document\Document;
use Illuminate\Support\Facades\Validator;
use Tests\Feature\FeatureTestCase;

class ItemQuantityTest extends FeatureTestCase
{
    public function testItShouldNotSaveADocumentWithANegativeQuantity()
    {
        foreach (['-1', '2-5', '-0.5'] as $quantity) {
            $request = $this->getRequest();
            $request['items'][0]['quantity'] = $quantity;

            $this->loginAs()
                ->withExceptionHandling()
                ->postJson(route('invoices.store'), $request)
                ->assertStatus(422)
                ->assertJsonValidationErrors('items.0.quantity');

            $this->assertDatabaseMissing('documents', ['document_number' => $request['document_number']]);
        }
    }

    public function testItShouldStillSaveADocumentWithAPositiveQuantity()
    {
        foreach (['3', '1.5', '2*3', '12'] as $quantity) {
            $request = $this->getRequest();
            $request['items'][0]['quantity'] = $quantity;

            $this->loginAs()
                ->postJson(route('invoices.store'), $request)
                ->assertOk()
                ->assertJson(['success' => true]);

            $this->assertDatabaseHas('documents', ['document_number' => $request['document_number']]);
        }
    }

    public function testItShouldNotImportAnItemWithANegativeQuantity()
    {
        $rules = (new DocumentItem())->setContainer($this->app)->rules();

        $this->assertTrue(Validator::make(['quantity' => '-1'], ['quantity' => $rules['quantity']])->fails());
        $this->assertTrue(Validator::make(['quantity' => ' -2.5'], ['quantity' => $rules['quantity']])->fails());

        $this->assertFalse(Validator::make(['quantity' => '1'], ['quantity' => $rules['quantity']])->fails());
        $this->assertFalse(Validator::make(['quantity' => '150'], ['quantity' => $rules['quantity']])->fails());
        $this->assertFalse(Validator::make(['quantity' => '0'], ['quantity' => $rules['quantity']])->fails());
    }

    protected function getRequest(): array
    {
        return Document::factory()->invoice()->items()->raw();
    }
}
