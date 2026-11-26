<?php

namespace Tests\Feature\Document;

use App\Models\Document\Document;
use Tests\Feature\FeatureTestCase;

class DiscountTest extends FeatureTestCase
{
    public function testItShouldNotSaveADocumentWithANegativeOrOverPercentageDiscount()
    {
        $cases = [
            [['discount' => -10, 'discount_type' => 'percentage'], 'discount'],
            [['discount' => 150, 'discount_type' => 'percentage'], 'discount'],
            [['discount' => -5, 'discount_type' => 'fixed'], 'discount'],
            [['items' => [['discount' => -5, 'discount_type' => 'fixed']]], 'items.0.discount'],
            [['items' => [['discount' => 120, 'discount_type' => 'percentage']]], 'items.0.discount'],
            // A fixed discount over the amount of the line, 2 x 50
            [['items' => [['price' => 50, 'quantity' => 2, 'discount' => 101, 'discount_type' => 'fixed']]], 'items.0.discount'],
            // A fixed discount of the document over what the lines leave, 100 - 10% for the only line
            [['items' => [['price' => 50, 'quantity' => 2, 'discount' => 10, 'discount_type' => 'percentage']], 'discount' => 91, 'discount_type' => 'fixed'], 'discount'],
        ];

        foreach ($cases as [$changes, $field]) {
            $request = $this->getRequest($changes);

            $this->loginAs()
                ->withExceptionHandling()
                ->postJson(route('invoices.store'), $request)
                ->assertStatus(422)
                ->assertJsonValidationErrors($field);

            $this->assertDatabaseMissing('documents', ['document_number' => $request['document_number']]);
        }
    }

    public function testItShouldStillSaveADocumentWithADiscount()
    {
        $cases = [
            ['discount' => 10, 'discount_type' => 'percentage'],
            ['discount' => 100, 'discount_type' => 'percentage'],
            ['discount' => 5, 'discount_type' => 'fixed'],
            ['items' => [['discount' => 20, 'discount_type' => 'percentage']]],
            ['discount' => 0],
            // The whole amount, of the line and then of the document
            ['items' => [['price' => 50, 'quantity' => 2, 'discount' => 100, 'discount_type' => 'fixed']]],
            ['items' => [['price' => 50, 'quantity' => 2, 'discount' => 10, 'discount_type' => 'percentage']], 'discount' => 90, 'discount_type' => 'fixed'],
        ];

        foreach ($cases as $changes) {
            $request = $this->getRequest($changes);

            $this->loginAs()
                ->postJson(route('invoices.store'), $request)
                ->assertOk()
                ->assertJson(['success' => true]);

            $this->assertDatabaseHas('documents', ['document_number' => $request['document_number']]);
        }
    }

    protected function getRequest(array $changes): array
    {
        $request = Document::factory()->invoice()->items()->raw();

        if (isset($changes['items'])) {
            foreach ($changes['items'] as $key => $item) {
                $request['items'][$key] = array_merge($request['items'][$key], $item);
            }

            unset($changes['items']);
        }

        return array_merge($request, $changes);
    }
}
