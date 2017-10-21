<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Model;
use App\Models\Company\Company;
use App\Models\Income\Invoice;
use App\Models\Income\InvoiceItem;
use App\Models\Income\InvoiceTotal;
use App\Models\Setting\Tax;

class CreateInvoiceTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_totals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('invoice_id');
            $table->string('code')->nullable();
            $table->string('name');
            $table->double('amount', 15, 4);
            $table->integer('sort_order');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Model::unguard();

        $companies = Company::all();

        foreach ($companies as $company) {
            $invoices = Invoice::where('company_id', $company->id)->get();

            foreach ($invoices as $invoice) {
                $invoice_items = InvoiceItem::where('company_id', $company->id)->where('invoice_id', $invoice->id)->get();

                $taxes = [];
                $tax_total = 0;
                $sub_total = 0;

                foreach ($invoice_items as $invoice_item) {
                    unset($tax_object);

                    $invoice_item->total = $invoice_item->price * $invoice_item->quantity;

                    if (!empty($invoice_item->tax_id)) {
                        $tax_object = Tax::where('company_id', $company->id)->where('id', $invoice_item->tax_id)->first();

                        $invoice_item->tax = (($invoice_item->price * $invoice_item->quantity) / 100) * $tax_object->rate;
                    }

                    $invoice_item->update();

                    if (isset($tax_object)) {
                        if (array_key_exists($invoice_item->tax_id, $taxes)) {
                            $taxes[$invoice_item->tax_id]['amount'] += $invoice_item->tax;
                        } else {
                            $taxes[$invoice_item->tax_id] = [
                                'name' => $tax_object->name,
                                'amount' => $invoice_item->tax
                            ];
                        }
                    }

                    $tax_total += $invoice_item->tax;
                    $sub_total += $invoice_item->price * $invoice_item->quantity;
                }

                $invoice->amount = $sub_total + $tax_total;

                $invoice->update();

                // Added invoice total sub total
                $invoice_sub_total = [
                    'company_id' => $company->id,
                    'invoice_id' => $invoice->id,
                    'code' => 'sub_total',
                    'name' => 'invoices.sub_total',
                    'amount' => $sub_total,
                    'sort_order' => 1,
                ];

                InvoiceTotal::create($invoice_sub_total);

                $sort_order = 2;

                // Added invoice total taxes
                if ($taxes) {
                    foreach ($taxes as $tax) {
                        $invoice_tax_total = [
                            'company_id' => $company->id,
                            'invoice_id' => $invoice->id,
                            'code' => 'tax',
                            'name' => $tax['name'],
                            'amount' => $tax['amount'],
                            'sort_order' => $sort_order,
                        ];

                        InvoiceTotal::create($invoice_tax_total);

                        $sort_order++;
                    }
                }

                // Added invoice total total
                $invoice_total = [
                    'company_id' => $company->id,
                    'invoice_id' => $invoice->id,
                    'code' => 'total',
                    'name' => 'invoices.total',
                    'amount' => $sub_total + $tax_total,
                    'sort_order' => $sort_order,
                ];

                InvoiceTotal::create($invoice_total);
            }
        }

        Model::reguard();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invoice_totals');
    }
}
