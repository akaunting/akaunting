<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Model;
use App\Models\Company\Company;
use App\Models\Expense\Bill;
use App\Models\Expense\BillItem;
use App\Models\Expense\BillTotal;
use App\Models\Setting\Tax;

class CreateBillTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_totals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('bill_id');
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
            $bills = Bill::where('company_id', $company->id)->get();

            foreach ($bills as $bill) {
                $bill_items = BillItem::where('company_id', $company->id)->where('bill_id', $bill->id)->get();

                $taxes = [];
                $tax_total = 0;
                $sub_total = 0;

                foreach ($bill_items as $bill_item) {
                    unset($tax_object);

                    $bill_item->total = $bill_item->price * $bill_item->quantity;

                    if (!empty($bill_item->tax_id)) {
                        $tax_object = Tax::where('company_id', $company->id)->where('id', $bill_item->tax_id)->first();

                        $bill_item->tax = (($bill_item->price * $bill_item->quantity) / 100) * $tax_object->rate;
                    }

                    $bill_item->update();

                    if (isset($tax_object)) {
                        if (array_key_exists($bill_item->tax_id, $taxes)) {
                            $taxes[$bill_item->tax_id]['amount'] += $bill_item->tax;
                        } else {
                            $taxes[$bill_item->tax_id] = [
                                'name' => $tax_object->name,
                                'amount' => $bill_item->tax
                            ];
                        }
                    }

                    $tax_total += $bill_item->tax;
                    $sub_total += $bill_item->price * $bill_item->quantity;
                }

                $bill->amount = $sub_total + $tax_total;

                $bill->update();

                // Added bill total sub total
                $bill_sub_total = [
                    'company_id' => $company->id,
                    'bill_id' => $bill->id,
                    'code' => 'sub_total',
                    'name' => 'bills.sub_total',
                    'amount' => $sub_total,
                    'sort_order' => 1,
                ];

                BillTotal::create($bill_sub_total);

                $sort_order = 2;

                // Added bill total taxes
                if ($taxes) {
                    foreach ($taxes as $tax) {
                        $bill_tax_total = [
                            'company_id' => $company->id,
                            'bill_id' => $bill->id,
                            'code' => 'tax',
                            'name' => $tax['name'],
                            'amount' => $tax['amount'],
                            'sort_order' => $sort_order,
                        ];

                        BillTotal::create($bill_tax_total);

                        $sort_order++;
                    }
                }

                // Added bill total total
                $bill_total = [
                    'company_id' => $company->id,
                    'bill_id' => $bill->id,
                    'code' => 'total',
                    'name' => 'bills.total',
                    'amount' => $sub_total + $tax_total,
                    'sort_order' => $sort_order,
                ];

                BillTotal::create($bill_total);
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
        Schema::drop('bill_totals');
    }
}
