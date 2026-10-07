<?php

namespace Tests\Feature\Common;

use App\Abstracts\Report as ReportClass;
use App\Events\Report\DataLoaded;
use App\Events\Report\DataLoading;
use App\Events\Report\RowsShowing;
use App\Exports\Common\Reports as ReportExport;
use App\Jobs\Auth\CreateUser;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Common\CreateCompany;
use App\Jobs\Common\UpdateReport;
use App\Listeners\Report\AddIncomeCategories;
use App\Listeners\Report\AddIncomeExpenseCategories;
use App\Models\Banking\Transaction;
use App\Models\Common\Company;
use App\Models\Common\Contact;
use App\Models\Common\Report;
use App\Models\Setting\Category;
use App\Reports\IncomeExpenseSummary;
use App\Reports\IncomeSummary;
use App\Reports\ProfitLoss;
use App\Traits\Permissions;
use App\Utilities\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Facade;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\Feature\FeatureTestCase;

class ReportsTest extends FeatureTestCase
{
    use Permissions;

    public function testItShouldShowProfitLossReportWhenSettingsAreMissing()
    {
        $report = Report::create([
            'company_id' => company_id(),
            'class' => 'App\Reports\ProfitLoss',
            'name' => 'Legacy Profit Loss',
            'description' => 'Report without saved settings',
            'settings' => null,
            'created_from' => 'core::test',
            'created_by' => $this->user->id,
        ]);

        $this->loginAs()
            ->get(route('reports.show', $report->id))
            ->assertOk()
            ->assertSeeText('Legacy Profit Loss')
            ->assertSeeText(trans('reports.net_profit'));
    }

    public function testItShouldRenderProfitLossFooterWhenIncomeTotalsAreMissing()
    {
        $report = new Report([
            'class' => ProfitLoss::class,
            'name' => 'Profit Loss Footer',
            'description' => 'Footer regression coverage',
            'settings' => ['group' => 'category', 'period' => 'quarterly', 'basis' => 'accrual'],
        ]);

        $class = new ProfitLoss($report, false);
        $class->dates = ['Q1 2026', 'Q2 2026'];
        $class->footer_totals = [
            'expense' => ['Q1 2026' => 125],
        ];

        $html = view('reports.profit_loss.table.footer', [
            'class' => $class,
            'table_key' => 'income',
        ])->render();

        $this->assertStringContainsString(trans_choice('general.totals', 1), $html);
        $this->assertStringNotContainsString('Undefined array key', $html);
    }

    public function testItShouldRenderMoneyComponentWhenAmountIsEmptyString()
    {
        $html = view('money::components.money', [
            'amount' => '',
            'currency' => 'USD',
            'convert' => false,
        ])->render();

        $this->assertNotEmpty($html);
        $this->assertStringNotContainsString('Invalid amount', $html);
    }

    public function testItShouldRefreshProfitAfterAppsAdjustTheReport()
    {
        $model = Report::where('class', ProfitLoss::class)->firstOrFail();

        $before = new ProfitLoss($model);

        // An app adding its amounts on DataLoaded, as CreditDebitNotes and Expenses do
        Event::listen(DataLoaded::class, function (DataLoaded $event) {
            if (! $event->class instanceof ProfitLoss) {
                return;
            }

            $date = array_key_first($event->class->footer_totals[Category::INCOME_TYPE]);

            $event->class->footer_totals[Category::INCOME_TYPE][$date] += 100;
            $event->class->footer_totals[Category::DIRECT_COST_TYPE][$date] += 30;
        });

        $after = new ProfitLoss($model);
        $date = array_key_first($after->net_profit);

        $this->assertEqualsWithDelta($before->gross_profit[$date] + 70, $after->gross_profit[$date], 0.001);
        $this->assertEqualsWithDelta($before->net_profit[$date] + 70, $after->net_profit[$date], 0.001);

        // Loading the same report again gives the same profit, not the sum of both loads
        $gross_profit = $after->gross_profit;
        $net_profit = $after->net_profit;

        $after->load();

        $this->assertEquals($gross_profit, $after->gross_profit);
        $this->assertEquals($net_profit, $after->net_profit);
    }

    public function testItShouldServeRepeatVisitsAndOutputsFromTheCache()
    {
        // Logging in sets the company_id URL default the route() calls below rely on
        $this->loginAs();

        $report = Report::where('class', ProfitLoss::class)->firstOrFail();
        $query = ['search' => 'basis:cash'];
        $url = route('reports.show', [$report->id] + $query);
        $loads = $this->countLoads();

        $this->travelTo(Date::parse('2026-09-28 10:15:00'));

        $miss = $this->loginAs()->get($url)->assertOk();
        $this->assertSame(1, $loads[$report->id]);

        $this->travelTo(Date::parse('2026-09-28 12:00:00'));

        $hit = $this->loginAs()->get($url)->assertOk()->assertSeeText(trans('reports.net_profit'));
        $this->assertSame(1, $loads[$report->id]);

        $class = $hit->viewData('class');
        $this->assertInstanceOf(ProfitLoss::class, $class);
        $this->assertNotSame($miss->viewData('class'), $class);
        $this->assertTrue($class->model->is($report));
        $this->assertSame('2026-09-28 10:15:00', $class->cached_at->toDateTimeString());

        // The refresh icon sits before Print, and its tooltip tells when the copy was built
        $built = Date::parse('2026-09-28 10:15:00')->locale(app()->getLocale());

        $hit->assertSeeInOrder(['show-more-actions-refresh-report', route('reports.print', [$report->id] + $query)], false)
            ->assertSee(trans('reports.last_updated', ['date' => company_date($built), 'time' => $built->isoFormat('LT')]));

        foreach (['print', 'pdf'] as $action) {
            $this->loginAs()->get(route('reports.' . $action, [$report->id] + $query))->assertOk();
        }

        // A test never sends the download, so remove the file laravel-excel leaves in storage/app/temp
        $export = $this->loginAs()->get(route('reports.export', [$report->id] + $query))->assertOk()->assertDownload();
        unlink($export->baseResponse->getFile()->getPathname());

        $this->assertSame(1, $loads[$report->id]);

        // When load() folds a query key into search, the output links still find the page's copy
        $page = $this->loginAs()->get(route('reports.show', [$report->id, 'basis' => 'cash']))->assertOk();
        $this->assertSame(2, $loads[$report->id]);

        $this->assertSame(1, preg_match('#href="([^"]*/reports/' . $report->id . '/print\?[^"]*)"#', $page->getContent(), $print));

        $this->loginAs()->get(html_entity_decode($print[1]))->assertOk();
        $this->assertSame(2, $loads[$report->id]);

        // A new day rebuilds: reports default to the current year, and some age amounts up to today
        $this->travelTo(Date::parse('2026-09-29 09:00:00'));

        $this->loginAs()->get($url)->assertOk();
        $this->assertSame(3, $loads[$report->id]);

        config(['report.cache.enabled' => false]);

        $this->loginAs()->get($url)->assertOk();
        $this->loginAs()->get($url)->assertOk()->assertDontSee('show-more-actions-refresh-report');
        $this->assertSame(5, $loads[$report->id]);
    }

    public function testItShouldRebuildOnRefreshAndSettingChangesOnly()
    {
        // A fixed clock, so no visit crosses midnight; logging in sets the company_id URL default
        $this->travelTo(Date::parse('2026-09-28 10:00:00'));
        $this->loginAs();

        $role = $this->createRole('profit-loss-reader');
        $this->attachPermission($role, 'read-admin-panel');
        $this->attachPermission($role, 'read-reports-profit-loss');

        $reader = $this->dispatch(new CreateUser(array_merge(user_model_class()::factory()->raw(), [
            'enabled' => 1,
            'companies' => [$this->company->id],
            'roles' => [$role->id],
        ])));

        $report = Report::where('class', ProfitLoss::class)->firstOrFail();
        $query = ['search' => 'basis:cash'];
        $url = route('reports.show', [$report->id] + $query);
        $loads = $this->countLoads();

        $this->loginAs()->get($url)->assertOk();
        $this->assertSame(1, $loads[$report->id]);

        // New data waits for a refresh; the transaction.number_next it moves is an ignored setting
        $this->dispatch(new CreateTransaction(Transaction::factory()->income()->raw()));

        $this->loginAs()->get($url)->assertOk();
        $this->assertSame(1, $loads[$report->id]);

        // Refresh comes back to the same URL and rebuilds
        $this->loginAs()->get(route('reports.clear', [$report->id] + $query))->assertRedirect($url);

        $this->loginAs()->get($url)->assertOk();
        $this->assertSame(2, $loads[$report->id]);

        // Per-user interface settings are ignored; the others rebuild
        setting(['favorites.report.' . $this->user->id => json_encode([$report->id])]);
        setting()->save();

        $this->loginAs()->get($url)->assertOk();
        $this->assertSame(2, $loads[$report->id]);

        setting(['localisation.percent_position' => 'before']);
        setting()->save();

        $this->loginAs()->get($url)->assertOk();
        $this->assertSame(3, $loads[$report->id]);

        // Saving the report's own settings rebuilds it
        $this->dispatch(new UpdateReport($report, [
            'settings' => ['group' => 'category', 'period' => 'monthly', 'basis' => 'accrual'],
        ]));

        $this->loginAs()->get($url)->assertOk();
        $this->assertSame(4, $loads[$report->id]);

        // Refresh needs read-common-reports, like the report pages
        $this->withExceptionHandling()
            ->loginAs($reader)
            ->get(route('reports.clear', [$report->id] + $query))
            ->assertForbidden();

        $this->loginAs()->get($url)->assertOk();
        $this->assertSame(4, $loads[$report->id]);
    }

    public function testItShouldKeepEntriesApartPerUserAndCompany()
    {
        // A fixed clock, so no visit crosses midnight
        $this->travelTo(Date::parse('2026-09-28 10:00:00'));
        $this->actingAs($this->user);

        $reader = $this->dispatch(new CreateUser(array_merge(user_model_class()::factory()->raw(), [
            'enabled' => 1,
            'companies' => [$this->company->id],
            'roles' => $this->user->roles->pluck('id')->all(),
        ])));

        $other = $this->dispatch(new CreateCompany(Company::factory()->enabled()->raw([
            'settings' => ['wizard.completed' => '1', 'email.protocol' => 'array'],
        ])));

        $mine = Report::where('class', ProfitLoss::class)->firstOrFail();
        $theirs = Report::withoutGlobalScopes()->where('company_id', $other->id)->where('class', ProfitLoss::class)->firstOrFail();
        $loads = $this->countLoads();

        // Same URL and permissions, another user: its own copy
        foreach ([null, $reader, null, $reader] as $user) {
            $this->loginAs($user, $this->company)->get(route('reports.show', $mine->id))->assertOk();
        }

        $this->assertSame(2, $loads[$mine->id]);

        // The other company keeps its own copy
        $this->loginAs(null, $other)->get(route('reports.show', ['company_id' => $other->id, 'report' => $theirs->id]))->assertOk();
        $this->loginAs(null, $this->company)->get(route('reports.show', $mine->id))->assertOk();

        $this->assertSame(1, $loads[$theirs->id]);
        $this->assertSame(2, $loads[$mine->id]);

        // Refresh rebuilds only the refreshed report
        $this->loginAs(null, $this->company)->get(route('reports.clear', $mine->id))->assertRedirect(route('reports.show', $mine->id));

        $this->loginAs(null, $other)->get(route('reports.show', ['company_id' => $other->id, 'report' => $theirs->id]))->assertOk();
        $this->loginAs(null, $this->company)->get(route('reports.show', $mine->id))->assertOk();

        $this->assertSame(1, $loads[$theirs->id]);
        $this->assertSame(3, $loads[$mine->id]);
    }

    public function testItShouldKeepEveryOtherRowWhenAChipExcludesOne()
    {
        $this->loginAs();

        $alpha = Contact::factory()->customer()->enabled()->create(['name' => 'Alpha Stores']);
        $beta = Contact::factory()->customer()->enabled()->create(['name' => 'Beta Stores']);

        foreach ([[$alpha, 100], [$beta, 40]] as [$contact, $amount]) {
            $this->dispatch(new CreateTransaction(Transaction::factory()->income()->raw([
                'contact_id' => $contact->id,
                'amount' => $amount,
                'paid_at' => '2026-05-10 10:00:00',
            ])));
        }

        $model = new Report([
            'class' => IncomeExpenseSummary::class,
            'name' => 'Income vs Expense by contact',
            'settings' => ['group' => 'contact', 'period' => 'monthly', 'basis' => 'cash'],
        ]);

        $window = ['start_date' => '2026-05-01', 'end_date' => '2026-05-31'];

        // "Is not" used to keep the left-out contact's row only, which the search string had emptied
        $report = $this->loadReport($model, $window + ['search' => 'not contact_id:' . $alpha->id]);

        $this->assertArrayNotHasKey($alpha->id, $report->row_values['income']);
        $this->assertEquals(['May 2026' => 40], $report->row_values['income'][$beta->id]);
        $this->assertEquals(['May 2026' => 40], $report->footer_totals['income']);

        $report = $this->loadReport($model, $window + ['search' => 'contact_id:' . $alpha->id]);

        $this->assertSame([$alpha->id], array_keys($report->row_values['income']));
        $this->assertEquals(['May 2026' => 100], $report->footer_totals['income']);

        // "Is not" keeps the records without a contact, which SQL's != and NOT IN alone never match, so "is" and
        // "is not" make up the whole (App\Utilities\SearchStringColumns)
        $this->dispatch(new CreateTransaction(Transaction::factory()->income()->raw([
            'contact_id' => null,
            'amount' => 25,
            'paid_at' => '2026-05-12 10:00:00',
        ])));

        $by_category = new Report([
            'class' => IncomeExpenseSummary::class,
            'name' => 'Income vs Expense by category',
            'settings' => ['group' => 'category', 'period' => 'monthly', 'basis' => 'cash'],
        ]);

        $total = fn (string $search) => array_sum($this->loadReport($by_category, $window + ['search' => $search])->footer_totals['income']);

        $this->assertEquals(165, $total(''));
        $this->assertEquals(100, $total('contact_id:' . $alpha->id));
        $this->assertEquals(65, $total('not contact_id:' . $alpha->id));
        $this->assertEquals(25, $total('not contact_id:' . $alpha->id . ',' . $beta->id));

        // A category is left out on its own, as the search string leaves out its records: a sub-category whose
        // parent is left out keeps its row and is listed at the top of the tree, where it can be seen
        $parent = Category::factory()->income()->enabled()->create(['name' => 'Rentals']);
        $child = Category::factory()->income()->enabled()->create(['name' => 'Garages', 'parent_id' => $parent->id]);
        $sibling = Category::factory()->income()->enabled()->create(['name' => 'Services']);

        $tree = function (string $search, string $report = IncomeSummary::class, string $listener = AddIncomeCategories::class) {
            $this->setRequest(['search' => $search]);

            $class = new $report(new Report([
                'class' => $report,
                'name' => 'Report by category',
                'settings' => ['group' => 'category', 'period' => 'monthly', 'basis' => 'cash'],
            ]), false);

            $class->setYear();
            $class->setTables();
            $class->setDates();

            // The core listener alone: with Double-Entry enabled the module builds every category's row itself
            (new $listener())->handleRowsShowing(new RowsShowing($class));

            return [array_keys($class->row_names['income']), $class->row_tree_nodes['income']];
        };

        [$rows, $nodes] = $tree('not category_id:' . $parent->id);

        $this->assertNotContains($parent->id, $rows);
        $this->assertContains($child->id, $rows);
        $this->assertContains($sibling->id, $rows);
        $this->assertArrayHasKey($child->id, $nodes);

        [$rows, $nodes] = $tree('category_id:' . $parent->id);

        $this->assertSame([$parent->id], $rows);
        $this->assertSame([$parent->id => null], $nodes);

        [$rows, $nodes] = $tree('category_id:' . $child->id);

        $this->assertSame([$child->id], $rows);
        $this->assertSame([$child->id => null], $nodes);

        // Profit & Loss narrows its rows too, so Double-Entry's tree of the kept categories, merged into core's,
        // lists each of them once
        [$rows, $nodes] = $tree('not category_id:' . $parent->id, ProfitLoss::class, AddIncomeExpenseCategories::class);

        $this->assertNotContains($parent->id, $rows);
        $this->assertContains($sibling->id, $rows);
        $this->assertArrayHasKey($child->id, $nodes);

        // See performance opens Income vs Expense on the account, without the year no report reads
        $account_id = setting('default.account');
        $performance = Report::where('class', IncomeExpenseSummary::class)->firstOrFail();

        $this->loginAs()
            ->get(route('accounts.see-performance', $account_id))
            ->assertRedirect(route('reports.show', ['report' => $performance->id, 'search' => 'basis:accrual account_id:' . $account_id]));
    }

    public function testItShouldListTheAppliedChipsAbovePrintPdfAndExcel()
    {
        // A fixed clock, so the default window is a known financial year
        $this->travelTo(Date::parse('2026-06-15 10:00:00'));
        $this->loginAs();

        // Ten contacts ahead of Zulu by name, so the chip's first values miss it and its name is looked up
        foreach (range(1, 10) as $number) {
            Contact::factory()->customer()->enabled()->create(['name' => 'Aardvark ' . $number]);
        }

        $zulu = Contact::factory()->customer()->enabled()->create(['name' => 'Zulu Traders']);

        $model = Report::where('class', ProfitLoss::class)->firstOrFail();
        $query = ['search' => 'basis:cash not contact_id:' . $zulu->id . ' "rent"', 'start_date' => '2026-03-01', 'end_date' => '2026-03-31'];
        $excluded = trans('reports.applied_filters.excluded', ['values' => 'Zulu Traders']);

        $report = $this->loadReport($model, $query);

        $this->assertArrayNotHasKey($zulu->id, $report->filters['contacts']);

        // The window, the chips of one value with the saved group and period, the lists, then the free text,
        // which narrows the data too
        $this->assertSame([
            [trans('reports.date_range'), trans('reports.applied_filters.dates', ['start' => company_date('2026-03-01'), 'end' => company_date('2026-03-31')])],
            [trans('general.group_by'), trans_choice('general.categories', 1)],
            [trans('general.basis'), trans('general.cash')],
            [trans('general.period'), trans('general.quarterly')],
            [trans_choice('general.contacts', 1), $excluded],
            [trans('general.search'), '"rent"'],
        ], $report->applied_filters);

        // A value the report rejects gives way to the setting it uses
        $this->setRequest(['search' => 'basis:foo']);

        $this->assertContains([trans('general.basis'), trans('general.accrual')], $report->getAppliedFilters());

        // "Is not" is printed only where the chip declares that its report honours it; elsewhere the value is
        $report->filters['staff'] = [7 => 'Sam Staff'];
        $report->filters['names']['staff'] = 'Staff member';

        $this->setRequest(['search' => 'not staff_id:7']);

        $this->assertContains(['Staff member', 'Sam Staff'], $report->getAppliedFilters());

        // With no dates the window is the financial year, and a saved preference with no chip is listed when it
        // differs from the report's default
        $model->settings = array_merge((array) $model->settings, ['show_percentage' => 'yes']);

        $defaults = $this->loadReport($model, [])->applied_filters;

        $this->assertSame([trans('reports.date_range'), trans('reports.applied_filters.dates', ['start' => company_date('2026-01-01'), 'end' => company_date('2026-12-31')])], $defaults[0]);
        $this->assertContains([trans('reports.percentage_of_income'), trans('general.yes')], $defaults);

        // A field the options bar changes per run prints the value of the run, not the saved one
        $this->assertNotContains([trans('reports.percentage_of_income'), trans('general.yes')], $this->loadReport($model, ['search' => 'show_percentage:no'])->applied_filters);

        $model->settings = array_merge((array) $model->settings, ['show_percentage' => 'no']);

        $this->assertContains([trans('reports.percentage_of_income'), trans('general.yes')], $this->loadReport($model, ['search' => 'show_percentage:yes'])->applied_filters);

        $model->refresh();

        $this->loginAs()
            ->get(route('reports.print', [$model->id] + $query))
            ->assertOk()
            ->assertSeeInOrder([$model->name, $excluded, trans('reports.net_profit')]);

        $this->loginAs()->get(route('reports.pdf', [$model->id] + $query))->assertOk();

        // A queued export is unserialised in a worker without the request, so the chips come from the load
        $report = unserialize(serialize($this->loadReport($model, $query)));
        $this->setRequest();

        $file = tempnam(sys_get_temp_dir(), 'report');

        try {
            file_put_contents($file, Excel::raw(new ReportExport($report->views[$report->type], $report), ExcelType::XLSX));

            $sheet = IOFactory::load($file)->getActiveSheet();
        } finally {
            unlink($file);
        }

        $rows = array_filter(
            $sheet->toArray(null, false, false, true),
            fn (array $row) => ! empty(array_filter($row, fn ($value) => ! in_array($value, [null, ''], true))),
        );

        $first = array_key_first($rows);
        $cells = array_column($rows, 'B', 'A');

        $this->assertSame(trans('reports.date_range'), $rows[$first]['A']);
        $this->assertSame($excluded, $cells[trans_choice('general.contacts', 1)] ?? null);
        $this->assertSame('"rent"', $cells[trans('general.search')] ?? null);

        // A value spans the period columns, so a long one does not widen the first of them
        $this->assertNotEmpty(preg_grep('/^B' . $first . ':/', $sheet->getMergeCells()));

        // A report that lists its chips itself leaves the list out
        $report->views['applied'] = null;

        $this->assertStringNotContainsString('rp-applied', view($report->views['print'], ['print' => true])->with('class', $report)->render());
    }

    /**
     * Load the report as a page request with this query would.
     */
    protected function loadReport(Report $model, array $query): ReportClass
    {
        $this->setRequest($query);

        return new $model->class($model);
    }

    /**
     * Bind a report page request with this query, as the queue worker binds an empty one.
     */
    protected function setRequest(array $query = []): void
    {
        app()->instance('request', Request::create('/' . company_id() . '/common/reports', 'GET', $query));

        Facade::clearResolvedInstance('request');
    }

    /**
     * Count report loads per report id. Not Event::fake(): modules change the data in these events.
     */
    protected function countLoads(): \ArrayObject
    {
        $loads = new \ArrayObject();

        Event::listen(DataLoading::class, function (DataLoading $event) use ($loads) {
            $id = $event->class->model?->id;

            $loads[$id] = ($loads[$id] ?? 0) + 1;
        });

        return $loads;
    }
}
