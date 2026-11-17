<?php

namespace Tests\Feature\Common;

use App\Jobs\Document\CreateDocument;
use App\Models\Common\Report;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use App\Models\Setting\Tax;
use App\Reports\ExpenseSummary;
use App\Reports\IncomeSummary;
use App\Utilities\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Tests\Feature\FeatureTestCase;

/**
 * On the accrual basis the reports count a document line by line. A line discount is in the line total already, the
 * discount on the document total is shared out over the lines, so the lines add up to the document before taxes,
 * whichever discounts and taxes it has.
 *
 * Every document has a line of 150 in one category and a line of 50 in another.
 */
class ReportDocumentDiscountTest extends FeatureTestCase
{
    public static function discounts(): array
    {
        // [line discounts of the two lines, discount on the total, taxes of the two lines]
        return [
            'line percentage'                      => [[['percentage', 10], null], null, [null, null]],
            'line amount'                          => [[['fixed', 15], null], null, [null, null]],
            'total percentage'                     => [[null, null], ['percentage', 10], [null, null]],
            'total amount'                         => [[null, null], ['fixed', 25], [null, null]],
            'line and total percentage'            => [[['percentage', 10], ['percentage', 20]], ['percentage', 10], [null, null]],
            'line and total amount'                => [[['fixed', 15], ['fixed', 5]], ['fixed', 25], [null, null]],
            'line amount and total percentage'     => [[['fixed', 15], null], ['percentage', 10], [null, null]],
            'line percentage and total amount'     => [[['percentage', 10], null], ['fixed', 25], [null, null]],
            'both with a normal tax'               => [[['percentage', 10], null], ['percentage', 10], ['normal', 'normal']],
            'both with an inclusive tax'           => [[['percentage', 10], null], ['percentage', 10], [null, 'inclusive']],
            'amounts with normal and inclusive'    => [[['fixed', 15], null], ['fixed', 25], ['normal', 'inclusive']],
            'total amount with inclusive taxes'    => [[null, null], ['fixed', 25], ['inclusive', 'inclusive']],
        ];
    }

    /**
     * @dataProvider discounts
     */
    public function testTheInvoiceLinesShouldAddUpToTheInvoiceBeforeTaxes(array $line_discounts, ?array $total_discount, array $taxes)
    {
        $this->assertLinesAddUp(Document::INVOICE_TYPE, IncomeSummary::class, 'income', $line_discounts, $total_discount, $taxes);
    }

    /**
     * @dataProvider discounts
     */
    public function testTheBillLinesShouldAddUpToTheBillBeforeTaxes(array $line_discounts, ?array $total_discount, array $taxes)
    {
        $this->assertLinesAddUp(Document::BILL_TYPE, ExpenseSummary::class, 'expense', $line_discounts, $total_discount, $taxes);
    }

    protected function assertLinesAddUp(string $type, string $report_class, string $table, array $line_discounts, ?array $total_discount, array $taxes): void
    {
        $this->loginAs();
        $this->travelTo(Date::parse('2024-12-15 12:00:00'));

        $category_type = ($type === Document::INVOICE_TYPE) ? Category::INCOME_TYPE : Category::EXPENSE_TYPE;
        $categories = [
            Category::factory()->enabled()->create(['type' => $category_type])->id,
            Category::factory()->enabled()->create(['type' => $category_type])->id,
        ];

        $document = $this->createDocument($type, $categories, $line_discounts, $total_discount, $taxes);
        $document->load('totals');

        $report = $this->loadReport($report_class);
        $rows = $report->row_values[$table] ?? [];

        $shown = array_sum(array_map(fn ($id) => array_sum($rows[$id] ?? []), $categories));

        // The document before taxes, and each line its own share of it
        $this->assertEqualsWithDelta($document->amount_without_tax, $shown, 0.01);
        $this->assertLineShares($document, $categories, $rows);
    }

    /**
     * Each line shows its total less its share of the discount on the document total, shared out by the line totals.
     */
    protected function assertLineShares(Document $document, array $categories, array $rows): void
    {
        $discount = (float) $document->totals->where('code', 'discount')->sum('amount');
        $items = $document->items()->get()->values();
        $items_total = (float) $items->sum('total');

        foreach ($items as $index => $item) {
            $expected = $item->total - ($items_total ? $discount * $item->total / $items_total : 0);

            $this->assertEqualsWithDelta($expected, array_sum($rows[$categories[$index]] ?? []), 0.01, 'Line ' . ($index + 1));
        }
    }

    protected function createDocument(string $type, array $categories, array $line_discounts, ?array $total_discount, array $taxes): Document
    {
        $items = [];

        foreach ([150, 50] as $index => $price) {
            $item = [
                'name' => 'Line ' . ($index + 1),
                'quantity' => 1,
                'price' => $price,
                'category_id' => $categories[$index],
                'tax_ids' => [],
                'discount' => 0,
                'discount_type' => 'percentage',
            ];

            if (! empty($line_discounts[$index])) {
                [$item['discount_type'], $item['discount']] = $line_discounts[$index];
            }

            if (! empty($taxes[$index])) {
                $item['tax_ids'] = [Tax::factory()->enabled()->create(['type' => $taxes[$index], 'rate' => 18])->id];
            }

            $items[] = $item;
        }

        $request = Document::factory()->$type()->raw();

        $request = array_merge($request, [
            'status' => ($type === Document::INVOICE_TYPE) ? 'sent' : 'received',
            'issued_at' => '2024-03-10 10:00:00',
            'due_at' => '2024-04-10 10:00:00',
            'items' => $items,
        ]);

        unset($request['discount'], $request['discount_type']);

        if ($total_discount) {
            [$request['discount_type'], $request['discount']] = $total_discount;
        }

        return $this->dispatch(new CreateDocument($request));
    }

    protected function loadReport(string $class)
    {
        $request = Request::create('/' . company_id() . '/common/reports', 'GET', [
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
        ]);

        app()->instance('request', $request);

        Facade::clearResolvedInstance('request');

        return new $class(new Report([
            'class' => $class,
            'name' => class_basename($class),
            'settings' => ['group' => 'category', 'period' => 'monthly', 'basis' => 'accrual'],
        ]));
    }
}
