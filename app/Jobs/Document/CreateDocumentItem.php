<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Models\Document\DocumentItem;
use App\Models\Document\DocumentItemTax;
use App\Models\Setting\Tax;
use Illuminate\Support\Str;

class CreateDocumentItem extends Job
{
    protected $document;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $document
     * @param  $request
     */
    public function __construct($document, $request)
    {
        $this->document = $document;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return DocumentItem
     */
    public function handle()
    {
        $item_id = !empty($this->request['item_id']) ? $this->request['item_id'] : 0;
        $precision = config('money.' . $this->document->currency_code . '.precision');

        $item_amount = (double) $this->request['price'] * (double) $this->request['quantity'];

        $discount = 0;
        $item_discounted_amount = $item_amount;

        // Apply line discount to amount
        if (!empty($this->request['discount'])) {
            $discount = $this->request['discount'];

            if ($this->request['discount_type'] === 'percentage') {
                $item_discounted_amount = $item_amount -= ($item_amount * ($this->request['discount'] / 100));
            } else {
                $item_discounted_amount = $item_amount -= $this->request['discount'];
            }
        }

        $tax_amount = 0;
        $item_taxes = [];
        $item_tax_total = 0;

        if (!empty($this->request['tax_ids'])) {
            $inclusives = $compounds = [];

            foreach ((array) $this->request['tax_ids'] as $tax_id) {
                $tax = Tax::find($tax_id);

                switch ($tax->type) {
                    case 'inclusive':
                        $inclusives[] = $tax;

                        break;
                    case 'compound':
                        $compounds[] = $tax;

                        break;
                    case 'fixed':
                        $tax_amount = $tax->rate * (double) $this->request['quantity'];

                        // @todo tax calculate check here
                        //$tax_amount = round(abs($tax_amount), $precision);

                        $item_taxes[] = [
                            'company_id' => $this->document->company_id,
                            'type' => $this->document->type,
                            'document_id' => $this->document->id,
                            'tax_id' => $tax_id,
                            'name' => $tax->name,
                            'amount' => $tax_amount,
                        ];

                        $item_tax_total += $tax_amount;

                        break;
                    case 'withholding':
                        $tax_amount = 0 - $item_discounted_amount * ($tax->rate / 100);

                        // @todo tax calculate check here
                        //$tax_amount = round(abs($tax_amount), $precision);

                        $item_taxes[] = [
                            'company_id' => $this->document->company_id,
                            'type' => $this->document->type,
                            'document_id' => $this->document->id,
                            'tax_id' => $tax_id,
                            'name' => $tax->name,
                            'amount' => $tax_amount,
                        ];

                        $item_tax_total += $tax_amount;

                        break;
                    default:
                        $tax_amount = $item_discounted_amount * ($tax->rate / 100);

                        // @todo tax calculate check here
                        //$tax_amount = round(abs($tax_amount), $precision);

                        $item_taxes[] = [
                            'company_id' => $this->document->company_id,
                            'type' => $this->document->type,
                            'document_id' => $this->document->id,
                            'tax_id' => $tax_id,
                            'name' => $tax->name,
                            'amount' => $tax_amount,
                        ];

                        $item_tax_total += $tax_amount;

                        break;
                }
            }

            if ($inclusives) {
                $item_amount = $item_discounted_amount + $item_tax_total;

                $item_base_rate = $item_amount / (1 + collect($inclusives)->sum('rate') / 100);

                foreach ($inclusives as $inclusive) {
                    $tax_amount = $item_base_rate * ($inclusive->rate / 100);

                    $tax_amount = round(abs($tax_amount), $precision);

                    $item_taxes[] = [
                        'company_id' => $this->document->company_id,
                        'type' => $this->document->type,
                        'document_id' => $this->document->id,
                        'tax_id' => $inclusive->id,
                        'name' => $inclusive->name,
                        'amount' => $tax_amount,
                    ];

                    $item_tax_total += $tax_amount;
                }

                if (!empty($this->request['discount_type']) && $this->request['discount_type'] === 'fixed') {
                    $item_amount = ($item_amount - $item_tax_total) - $discount;
                } else {
                    $item_amount = ($item_amount - $item_tax_total) / (1 - $discount / 100);
                }
            }

            if ($compounds) {
                foreach ($compounds as $compound) {
                    $tax_amount = (($item_discounted_amount + $item_tax_total) / 100) * $compound->rate;

                    $tax_amount = round(abs($tax_amount), $precision);

                    $item_taxes[] = [
                        'company_id' => $this->document->company_id,
                        'type' => $this->document->type,
                        'document_id' => $this->document->id,
                        'tax_id' => $compound->id,
                        'name' => $compound->name,
                        'amount' => $tax_amount,
                    ];

                    $item_tax_total += $tax_amount;
                }
            }
        }

        $this->request['company_id'] = $this->document->company_id;
        $this->request['type'] = $this->document->type;
        $this->request['document_id'] = $this->document->id;
        $this->request['item_id'] = $item_id;
        $this->request['name'] = Str::limit($this->request['name'], 180, '');
        $this->request['description'] = !empty($this->request['description']) ? $this->request['description'] : '';
        $this->request['quantity'] = (double) $this->request['quantity'];
        $this->request['price'] = round($this->request['price'], $precision);
        $this->request['tax'] = round($item_tax_total, $precision);
        $this->request['discount_type'] = !empty($this->request['discount_type']) ? $this->request['discount_type'] : 'percentage';
        $this->request['discount_rate'] = !empty($this->request['discount']) ? $this->request['discount'] : 0;
        $this->request['total'] = round($item_amount, $precision);

        $document_item = DocumentItem::create($this->request);

        $document_item->item_taxes = false;
        $document_item->inclusives = false;
        $document_item->compounds = false;

        if (!empty($item_taxes)) {
            $document_item->item_taxes = $item_taxes;
            $document_item->inclusives = $inclusives;
            $document_item->compounds = $compounds;

            foreach ($item_taxes as $item_tax) {
                $item_tax['document_item_id'] = $document_item->id;
                $item_tax['amount'] = round(abs($item_tax['amount']), $precision);

                DocumentItemTax::create($item_tax);
            }
        }

        return $document_item;
    }
}
