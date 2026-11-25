<?php

namespace Tests\Feature\Common;

use App\Jobs\Document\CreateDocument;
use App\Models\Common\Report;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use App\Reports\ExpenseSummary;
use App\Reports\IncomeSummary;
use App\Reports\ProfitLoss;
use App\Utilities\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Tests\Feature\FeatureTestCase;

/**
 * An accrual report by category shows the discount on the document total shared out over the categories, or, with
 * discount_display:separate, on a row of its own with the categories at their amount before it. Both come to the
 * same totals.
 *
 * Every document has a line of 150 in one category and a line of 50 in another, and 10% off its total: 180.
 */
class ReportDiscountDisplayTest extends FeatureTestCase
{
    public function testItShouldShowTheSalesDiscountOnARowOfItsOwn()
    {
        $this->skipWhereTheDiscountIsOnItsAccounts();

        [$first, $second] = $this->createDocument(Document::INVOICE_TYPE, Category::INCOME_TYPE, Category::INCOME_TYPE);

        $report = $this->loadReport(IncomeSummary::class, 'discount_display:separate');

        $this->assertRow(150, $report, 'income', $first);
        $this->assertRow(50, $report, 'income', $second);
        $this->assertRow(-20, $report, 'income', ProfitLoss::DISCOUNT_ROW);
        $this->assertSame(trans('reports.discount_display.sales'), $report->row_names['income'][ProfitLoss::DISCOUNT_ROW]);
        $this->assertEqualsWithDelta(180, array_sum($report->footer_totals['income']), 0.01);

        $spread = $this->loadReport(IncomeSummary::class, '');

        $this->assertRow(135, $spread, 'income', $first);
        $this->assertArrayNotHasKey(ProfitLoss::DISCOUNT_ROW, $spread->row_values['income']);
        $this->assertEqualsWithDelta(180, array_sum($spread->footer_totals['income']), 0.01);
    }

    /**
     * The donut shows each category's share of the sales before the discount, which is no slice of them.
     */
    public function testTheDonutShouldLeaveOutTheDiscountRow()
    {
        [$first, $second] = $this->createDocument(Document::INVOICE_TYPE, Category::INCOME_TYPE, Category::INCOME_TYPE);

        $report = $this->loadReport(IncomeSummary::class, 'discount_display:separate');

        $donut = $report->getDonutChart('income');

        $labels = array_combine($donut->getLabels(), $donut->getSeries());

        $this->assertArrayNotHasKey(trans('reports.discount_display.sales'), $labels);
        $this->assertEquals(75, $labels[$report->row_names['income'][$first]]);
        $this->assertEquals(25, $labels[$report->row_names['income'][$second]]);
    }

    public function testItShouldShowThePurchaseDiscountOnARowOfItsOwn()
    {
        $this->skipWhereTheDiscountIsOnItsAccounts();

        [$first, $second] = $this->createDocument(Document::BILL_TYPE, Category::EXPENSE_TYPE, Category::EXPENSE_TYPE);

        $report = $this->loadReport(ExpenseSummary::class, 'discount_display:separate');

        $this->assertRow(150, $report, 'expense', $first);
        $this->assertRow(50, $report, 'expense', $second);
        $this->assertRow(-20, $report, 'expense', ProfitLoss::DISCOUNT_ROW);
        $this->assertSame(trans('reports.discount_display.purchases'), $report->row_names['expense'][ProfitLoss::DISCOUNT_ROW]);
        $this->assertEqualsWithDelta(180, array_sum($report->footer_totals['expense']), 0.01);
    }

    /**
     * A bill with a line of direct cost and one of expense takes its discount off each table by its share.
     */
    public function testItShouldShareThePurchaseDiscountOverTheCostTablesOfTheProfitAndLoss()
    {
        $this->skipWhereTheDiscountIsOnItsAccounts();

        [$sales, $other_sales] = $this->createDocument(Document::INVOICE_TYPE, Category::INCOME_TYPE, Category::INCOME_TYPE);
        [$cost, $expense] = $this->createDocument(Document::BILL_TYPE, Category::DIRECT_COST_TYPE, Category::EXPENSE_TYPE);

        $report = $this->loadReport(ProfitLoss::class, 'discount_display:separate');

        $this->assertRow(-20, $report, Category::INCOME_TYPE, ProfitLoss::DISCOUNT_ROW);
        $this->assertRow(150, $report, Category::DIRECT_COST_TYPE, $cost);
        $this->assertRow(-15, $report, Category::DIRECT_COST_TYPE, ProfitLoss::DISCOUNT_ROW);
        $this->assertRow(50, $report, Category::EXPENSE_TYPE, $expense);
        $this->assertRow(-5, $report, Category::EXPENSE_TYPE, ProfitLoss::DISCOUNT_ROW);

        $spread = $this->loadReport(ProfitLoss::class, '');

        // The same profit either way: 180 of income, 135 of cost and 45 of expenses
        $this->assertEqualsWithDelta(45, array_sum($report->gross_profit), 0.01);
        $this->assertEqualsWithDelta(0, array_sum($report->net_profit), 0.01);
        $this->assertEqualsWithDelta(array_sum($spread->gross_profit), array_sum($report->gross_profit), 0.01);
        $this->assertEqualsWithDelta(array_sum($spread->net_profit), array_sum($report->net_profit), 0.01);
    }

    /**
     * A categories filter picks categories, which the discount is none of: the category shows at its amount
     * before the discount and the discount row is left out. Core matches the filter against the document's own
     * category, so the invoice is in the first one.
     */
    public function testItShouldLeaveTheDiscountRowOutUnderACategoriesFilter()
    {
        [$first] = $this->createDocument(Document::INVOICE_TYPE, Category::INCOME_TYPE, Category::INCOME_TYPE, header_category: 0);

        $report = $this->loadReport(IncomeSummary::class, 'discount_display:separate category_id:' . $first);

        $this->assertRow(150, $report, 'income', $first);
        $this->assertArrayNotHasKey(ProfitLoss::DISCOUNT_ROW, $report->row_values['income']);
    }

    /**
     * By customer, or on the cash basis, the discount is shared out whatever the search asks: a customer has no
     * row for it, and a payment has it taken off already.
     */
    public function testItShouldShareTheDiscountOutByCustomerAndOnTheCashBasis()
    {
        $this->createDocument(Document::INVOICE_TYPE, Category::INCOME_TYPE, Category::INCOME_TYPE);

        $by_customer = $this->loadReport(IncomeSummary::class, 'discount_display:separate', ['group' => 'customer'], save: true);

        $this->assertFalse($by_customer->showsDiscountRow());
        $this->assertArrayNotHasKey(ProfitLoss::DISCOUNT_ROW, $by_customer->row_values['income'] ?? []);
        $this->assertEqualsWithDelta(180, array_sum($by_customer->footer_totals['income']), 0.01);
        $this->assertNull($this->getSwitch($by_customer));

        $cash = $this->loadReport(IncomeSummary::class, 'discount_display:separate', ['basis' => 'cash'], save: true);

        $this->assertFalse($cash->showsDiscountRow());
        $this->assertNull($this->getSwitch($cash));

        // Not listed as a saved preference either, as it does not apply
        $this->assertNotContains(trans('reports.discount_display.title'), array_column($this->getControls($cash)['readonly'], 'label'));
    }

    /**
     * The report's own setting picks it, without a search: the switch on the options bar shows it picked, and the
     * search can still switch it back.
     */
    public function testItShouldShowTheDiscountRowAsTheReportIsSet()
    {
        $this->skipWhereTheDiscountIsOnItsAccounts();

        [$first] = $this->createDocument(Document::INVOICE_TYPE, Category::INCOME_TYPE, Category::INCOME_TYPE);

        // Saved, as the options bar links to the report
        $report = $this->loadReport(IncomeSummary::class, '', ['discount_display' => 'separate'], save: true);

        $this->assertRow(150, $report, 'income', $first);
        $this->assertRow(-20, $report, 'income', ProfitLoss::DISCOUNT_ROW);

        $switch = $this->getSwitch($report);

        $this->assertSame('switch', $switch['kind']);
        $this->assertSame('separate', $switch['value']);

        $spread = $this->loadReport(IncomeSummary::class, 'discount_display:spread', ['discount_display' => 'separate']);

        $this->assertRow(135, $spread, 'income', $first);
        $this->assertArrayNotHasKey(ProfitLoss::DISCOUNT_ROW, $spread->row_values['income']);
    }

    /**
     * It is one of the report's settings, so the report form offers it with the others.
     */
    public function testItShouldBeOneOfTheReportSettings()
    {
        foreach ([IncomeSummary::class, ExpenseSummary::class, ProfitLoss::class] as $class) {
            $field = collect($this->loadReport($class, '')->getFields())->firstWhere('name', 'discount_display');

            $this->assertNotNull($field, $class);
            $this->assertSame('spread', $field['selected']);
        }
    }

    public function testItShouldOfferTheSwitchOnTheAccrualBasisByCategory()
    {
        $this->loginAs();

        $switch = $this->getSwitch($this->loadReport(ProfitLoss::class, '', save: true));

        $this->assertSame('switch', $switch['kind']);
        $this->assertSame(trans('reports.discount_display.title'), $switch['label']);
        $this->assertSame([ProfitLoss::DISCOUNT_DISPLAY_SPREAD, ProfitLoss::DISCOUNT_DISPLAY_SEPARATE], array_column($switch['options'], 0));
        $this->assertSame(ProfitLoss::DISCOUNT_DISPLAY_SPREAD, $switch['value']);

        // A display option, in the Customise panel when the run picks it too, not on the strip
        $controls = $this->getControls($this->loadReport(ProfitLoss::class, 'discount_display:separate', save: true));

        $this->assertSame(ProfitLoss::DISCOUNT_DISPLAY_SEPARATE, collect($controls['display'])->firstWhere('key', 'discount_display')['value']);
        $this->assertNull(collect($controls['strip'])->firstWhere('key', 'discount_display'));
    }

    /**
     * The Double-Entry app lists the discount on the accounts it is booked to, the sales and the purchase discount
     * accounts, in place of the discount row, and tests that itself (DocumentDiscountAccountsTest).
     */
    protected function skipWhereTheDiscountIsOnItsAccounts(): void
    {
        if (module_is_enabled('double-entry')) {
            $this->markTestSkipped('The Double-Entry app lists the discount on its accounts, not on the discount row.');
        }
    }

    protected function getControls($report): array
    {
        return $report->getFilterControls();
    }

    protected function getSwitch($report): ?array
    {
        return collect($this->getControls($report)['display'])->firstWhere('key', 'discount_display');
    }

    protected function assertRow(float $expected, $report, string $table, $row): void
    {
        $this->assertEqualsWithDelta($expected, array_sum($report->row_values[$table][$row] ?? []), 0.01, $table . ' row ' . $row);
    }

    /**
     * A document with a line of 150 and a line of 50, in two new categories of the types given, and 10% off its
     * total. Returns the ids of the two categories.
     */
    protected function createDocument(string $type, string $first_type, string $second_type, ?int $header_category = null): array
    {
        $this->loginAs();
        $this->travelTo(Date::parse('2024-12-15 12:00:00'));

        $categories = [
            Category::factory()->enabled()->create(['type' => $first_type])->id,
            Category::factory()->enabled()->create(['type' => $second_type])->id,
        ];

        $items = [];

        foreach ([150, 50] as $index => $price) {
            $items[] = [
                'name' => 'Line ' . ($index + 1),
                'quantity' => 1,
                'price' => $price,
                'category_id' => $categories[$index],
                'tax_ids' => [],
                'discount' => 0,
            ];
        }

        $request = array_merge(Document::factory()->$type()->raw(), [
            'status' => ($type === Document::INVOICE_TYPE) ? 'sent' : 'received',
            'issued_at' => '2024-03-10 10:00:00',
            'due_at' => '2024-04-10 10:00:00',
            'discount' => 10,
            'discount_type' => 'percentage',
            'items' => $items,
        ]);

        // The document's own category, as one of its lines (by index), when asked
        if (! is_null($header_category)) {
            $request['category_id'] = $categories[$header_category];
        }

        $this->dispatch(new CreateDocument($request));

        return $categories;
    }

    protected function loadReport(string $class, string $search, array $settings = [], bool $save = false)
    {
        $request = Request::create('/' . company_id() . '/common/reports', 'GET', [
            'search' => $search,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
        ]);

        app()->instance('request', $request);

        Facade::clearResolvedInstance('request');

        $model = new Report([
            'company_id' => company_id(),
            'class' => $class,
            'name' => class_basename($class),
            'description' => class_basename($class),
            'settings' => array_merge(['group' => 'category', 'period' => 'monthly', 'basis' => 'accrual'], $settings),
        ]);

        if ($save) {
            $model->save();
        }

        return new $class($model);
    }
}
