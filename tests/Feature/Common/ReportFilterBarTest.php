<?php

namespace Tests\Feature\Common;

use App\Abstracts\Report as ReportClass;
use App\Events\Report\FilterShowing;
use App\Jobs\Auth\CreateUser;
use App\Listeners\Report\AddAsOf;
use App\Models\Common\Contact;
use App\Models\Common\Report;
use App\Models\Setting\Category;
use App\Reports\ProfitLoss;
use App\Reports\TaxSummary;
use App\Traits\DateTime;
use App\Traits\Permissions;
use App\Utilities\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Tests\Feature\FeatureTestCase;

/**
 * Covers the report options bar's definition, App\Abstracts\Report::getFilterControls(), and the page it renders on.
 */
class ReportFilterBarTest extends FeatureTestCase
{
    use Permissions;

    public function testItShouldDescribeTheProfitAndLossControlsWithTheValuesApplied()
    {
        $this->loginAs();

        $category = Category::factory()->income()->enabled()->create(['name' => 'Rent received']);

        $controls = $this->getControls(ProfitLoss::class, [
            // The dates win over a date_range: term, which no report reads and Update drops; year: is dead
            'search' => 'basis:cash period:monthly not category_id:' . $category->id . ' "rent" year:2026 date_range:2026-01-01-to-2026-03-31',
            'start_date' => '2026-04-01',
            'end_date' => '2026-06-30',
        ]);

        $this->assertSame(['date_range', 'basis', 'period', 'group'], array_column($controls['strip'], 'key'));
        $this->assertSame(['range', 'switch', 'select', 'select'], array_column($controls['strip'], 'kind'));
        $this->assertSame(['start' => '2026-04-01', 'end' => '2026-06-30'], $controls['strip'][0]['value']);
        $this->assertSame(['cash', 'monthly', 'category'], array_column(array_slice($controls['strip'], 1), 'value'));

        // Show % of Income opts in with placement, so it is a display switch, not a saved preference
        $this->assertSame([['key' => 'show_percentage', 'kind' => 'switch', 'value' => 'no']], array_map(
            fn ($control) => array_intersect_key($control, array_flip(['key', 'kind', 'value'])),
            $controls['display'],
        ));
        $this->assertSame([], $controls['readonly']);

        $filters = collect($controls['filters'])->keyBy('key');

        $this->assertSame(['contact_id', 'category_id'], $filters->keys()->all());
        $this->assertTrue($filters['category_id']['exclude']);
        $this->assertSame(['ids' => [(string) $category->id], 'not' => true], $filters['category_id']['value']);
        $this->assertSame(['ids' => [], 'not' => false], $filters['contact_id']['value']);

        // Only the free text stays word for word
        $this->assertSame(['"rent"'], $controls['extras']);

        $this->assertSame([trans('reports.date_range'), company_date('2026-04-01') . ' – ' . company_date('2026-06-30')], $controls['summary'][0]);
        $this->assertSame([$controls['strip'][1]['label'], trans('general.cash')], $controls['summary'][1]);

        $this->assertTrue($controls['has_query']);
        $this->assertSame(route('reports.show', $controls['id']), $controls['action']);
        $this->assertSame(trans('reports.filter_bar.update'), $controls['texts']['update']);
    }

    public function testItShouldKeepTheTermsNoControlCanShowWordForWord()
    {
        // On the financial year's last day Year to Date would take This Year's place among the presets
        $this->travelTo(Date::parse('2026-06-15'));

        $this->loginAs();

        $controls = $this->getControls(ProfitLoss::class, [
            // A range operator gives an array, which no checklist writes; foo is no chip of the report
            'search' => 'contact_id>=5 not foo:1 "rent" "rent"',
        ]);

        $this->assertSame(['contact_id>=5', 'not foo:1', '"rent"'], $controls['extras']);
        $this->assertSame(['ids' => [], 'not' => false], collect($controls['filters'])->firstWhere('key', 'contact_id')['value']);

        // With no dates the window is the financial year, named after its preset
        [$start, $end] = (new class { use DateTime; })->getStartAndEndDates();

        $range = $controls['strip'][0];

        $this->assertSame(['start' => $start->toDateString(), 'end' => $end->toDateString()], $range['value']);
        $this->assertContains(['start' => $start->toDateString(), 'end' => $end->toDateString(), 'label' => trans('general.date_range.this_year')], $range['presets']);
        $this->assertFalse($this->getControls(ProfitLoss::class, [])['has_query']);

        // Overdue's value spans two terms and stays one; a negated amount is no bound the readers take, while a
        // negated setting is read past, as getFieldValue() does; as_of:yesterday is a date to its readers, and a
        // single date of any key sits in the as_of slot
        $overdue = 'partial,sent,viewed due_at<=today';
        $prepared = null;

        $controls = $this->getControls(ProfitLoss::class, [
            'search' => 'not status:' . $overdue . ' not min_amount:100 max_amount:500 not basis:cash as_of:yesterday',
        ], function (ReportClass $report) use ($overdue, &$prepared) {
            $report->filters['statuses'] = ['paid' => 'Paid', $overdue => 'Overdue'];
            $report->filters['min_amounts'] = [];
            $report->filters['max_amounts'] = [];
            $report->filters['as_of'] = [];
            $report->filters['closed_at'] = ['2026-03-31' => 'End of Last Quarter'];
            $report->filters['keys'] = array_merge($report->filters['keys'] ?? [], [
                'statuses' => 'status',
                'min_amounts' => 'min_amount',
                'max_amounts' => 'max_amount',
                'as_of' => 'as_of',
                'closed_at' => 'closed_at',
            ]);

            $prepared = $report;
        });

        $filters = collect($controls['filters'])->keyBy('key');
        $strip = collect($controls['strip'])->keyBy('key');

        $this->assertSame('', $filters['status']['value']);
        $this->assertSame(['min' => '', 'max' => '500'], $filters['amount']['value']);
        $this->assertSame('cash', $strip['basis']['value']);
        $this->assertSame('2026-06-14', $strip['as_of']['value']);
        $this->assertSame(['date_range', 'as_of', 'closed_at', 'basis', 'period', 'group'], $strip->keys()->all());
        $this->assertSame(['not status:' . $overdue, 'not min_amount:100'], $controls['extras']);

        // Print, PDF and Excel state the same date
        $this->assertContains([$prepared->getFilterLabel('as_of'), company_date('2026-06-14')], $prepared->getAppliedFilters());

        // Shown by its filter, the value that spans two terms is the filter's alone
        $controls = $this->getControls(ProfitLoss::class, ['search' => 'status:' . $overdue . ' "rent"'], function (ReportClass $report) use ($overdue) {
            $report->filters['statuses'] = ['paid' => 'Paid', $overdue => 'Overdue'];
            $report->filters['keys'] = array_merge($report->filters['keys'] ?? [], ['statuses' => 'status']);
        });

        $this->assertSame($overdue, collect($controls['filters'])->firstWhere('key', 'status')['value']);
        $this->assertSame(['"rent"'], $controls['extras']);
    }

    public function testItShouldNameChosenValuesBeyondTheLoadedOnes()
    {
        $this->loginAs();

        // The listeners load the first select_limit values by name, so one past them needs its name looked up
        $limit = (int) setting('default.select_limit', 10);

        Contact::factory()->count($limit)->customer()->enabled()->create();
        $last = Contact::factory()->customer()->enabled()->create(['name' => 'Zz Last Customer']);

        $controls = $this->getControls(ProfitLoss::class, ['search' => 'contact_id:' . $last->id]);
        $contact = collect($controls['filters'])->firstWhere('key', 'contact_id');

        $this->assertNotContains((string) $last->id, array_column($contact['options'], 0));
        $this->assertSame([(string) $last->id => 'Zz Last Customer'], (array) $contact['labels']);
        $this->assertSame(['ids' => [(string) $last->id], 'not' => false], $contact['value']);
        $this->assertTrue($contact['more']);
        $this->assertTrue($contact['remote']['allowed']);
        $this->assertStringContainsString('search=', $contact['remote']['url']);
    }

    public function testItShouldOfferTheRemoteSearchOnlyToUsersWhoMayReadTheList()
    {
        // A role that may read the reports and the customers, but not the categories
        $role = $this->createRole('report-reader');
        $this->attachPermission($role, 'read-admin-panel');
        $this->attachPermission($role, 'read-common-reports');
        $this->attachPermission($role, 'read-sales-customers');

        $reader = $this->dispatch(new CreateUser(array_merge(user_model_class()::factory()->raw(), [
            'enabled' => 1,
            'companies' => [$this->company->id],
            'roles' => [$role->id],
        ])));

        $this->loginAs($reader);

        $filters = collect($this->getControls(ProfitLoss::class, [])['filters'])->keyBy('key');

        // The categories listing asks for read-settings-categories, which this role lacks; the contacts one sets
        // its own permission, so its answer decides
        $this->assertFalse($filters['category_id']['remote']['allowed']);
        $this->assertTrue($filters['contact_id']['remote']['allowed']);
    }

    public function testItShouldOfferTaxSummaryWithholdingAsADisplaySwitch()
    {
        $this->loginAs();

        $controls = $this->getControls(TaxSummary::class, ['search' => 'withholding:yes']);

        $this->assertSame('withholding', $controls['display'][0]['key']);
        $this->assertSame('switch', $controls['display'][0]['kind']);
        $this->assertSame('yes', $controls['display'][0]['value']);
        $this->assertSame(['date_range', 'basis', 'period'], array_column($controls['strip'], 'key'));
    }

    public function testItShouldRenderTheBarInPlaceOfTheChipBar()
    {
        $report = Report::where('class', ProfitLoss::class)->first();

        $this->loginAs()
            ->get(route('reports.show', [$report->id, 'search' => 'basis:cash']))
            ->assertOk()
            ->assertSee('<report-filter', false)
            ->assertSee('id="report-content"', false)
            ->assertDontSee('<akaunting-search', false);
    }

    public function testItShouldNameTodaysPeriodsWhateverWindowIsAsked()
    {
        $this->travelTo(Date::parse('2026-10-15'));

        // A window in the first quarter: the period lists stop at its end, which today is past
        $this->setRequest(['start_date' => '2026-01-01', 'end_date' => '2026-03-31']);

        $shortcuts = (new class { use DateTime; })->getDatePickerShortcuts();

        $this->assertSame(['start' => '2026-10-01', 'end' => '2026-12-31'], $shortcuts[trans('general.date_range.this_quarter')]);
        $this->assertSame(['start' => '2026-10-01', 'end' => '2026-10-31'], $shortcuts[trans('general.date_range.this_month')]);
        $this->assertSame(['start' => '2026-07-01', 'end' => '2026-09-30'], $shortcuts[trans('general.date_range.previous_quarter')]);

        // The previous period ends the day before this one starts, so a quarter to 30 June follows one to 31 March
        $this->travelTo(Date::parse('2026-05-15'));

        $shortcuts = (new class { use DateTime; })->getDatePickerShortcuts();

        $this->assertSame(['start' => '2026-01-01', 'end' => '2026-03-31'], $shortcuts[trans('general.date_range.previous_quarter')]);
        $this->assertSame(['start' => '2026-04-01', 'end' => '2026-04-30'], $shortcuts[trans('general.date_range.previous_month')]);
        $this->assertSame(['start' => '2026-05-07', 'end' => '2026-05-13'], $shortcuts[trans('general.date_range.previous_week')]);
        $this->assertSame(['start' => '2025-01-01', 'end' => '2025-12-31'], $shortcuts[trans('general.date_range.previous_year')]);

        // A year from April named by its end: on 15 November 2026 this year is the one to 31 March 2027, named 2027,
        // and a report with no dates opens on it and its quarters
        $this->travelTo(Date::parse('2026-11-15'));

        $this->loginAs();

        setting(['localisation.financial_start' => '01-04', 'localisation.financial_denote' => 'ends']);

        $this->setRequest();

        $shortcuts = (new class { use DateTime; })->getDatePickerShortcuts();

        $this->assertSame(['start' => '2026-04-01', 'end' => '2027-03-31'], $shortcuts[trans('general.date_range.this_year')]);
        $this->assertSame(['start' => '2026-10-01', 'end' => '2026-12-31'], $shortcuts[trans('general.date_range.this_quarter')]);
        $this->assertSame(['start' => '2025-04-01', 'end' => '2026-03-31'], $shortcuts[trans('general.date_range.previous_year')]);

        $report = new ProfitLoss(Report::where('class', ProfitLoss::class)->first());

        $this->assertSame(2027, $report->year);
        $this->assertSame(['2026-04-01', '2027-03-31'], array_map(fn ($date) => $date->toDateString(), $report->getStartAndEndDates($report->year)));
        $this->assertSame(['2026-04-01', '2026-07-01', '2026-10-01', '2027-01-01'], array_map(fn ($quarter) => $quarter->getStartDate()->toDateString(), $report->getFinancialQuarters($report->year)));

        $range = collect($report->getFilterControls()['strip'])->firstWhere('key', 'date_range');

        $this->assertSame(['start' => '2026-04-01', 'end' => '2027-03-31'], $range['value']);
    }

    public function testItShouldOfferTheAsOfDateWithFinancialYearPresets()
    {
        // In the first month of a year from April, named by its end: last month, last quarter and last year all end
        // on 31 March, and the bar lists each
        $this->travelTo(Date::parse('2026-04-15 10:00:00'));

        $this->loginAs();

        setting(['localisation.financial_start' => '01-04', 'localisation.financial_denote' => 'ends']);

        // As an App lists its reports
        $listener = new class extends AddAsOf {
            protected $classes = [ProfitLoss::class];
        };

        $controls = $this->getControls(ProfitLoss::class, ['search' => 'as_of:yesterday'], fn (ReportClass $report) => $listener->handleFilterShowing(new FilterShowing($report)));

        $as_of = collect($controls['strip'])->firstWhere('key', 'as_of');

        $this->assertSame(['date_range', 'as_of', 'basis', 'period', 'group'], array_column($controls['strip'], 'key'));
        $this->assertSame('as_of', $as_of['kind']);
        $this->assertSame(trans('reports.as_of'), $as_of['label']);
        $this->assertSame('2026-04-14', $as_of['value']);

        // The applied date is no preset
        $this->assertSame([
            ['date' => '2026-04-15', 'label' => trans('general.date_range.today')],
            ['date' => '2026-04-30', 'label' => trans('reports.as_of_presets.end_of_this_month')],
            ['date' => '2026-03-31', 'label' => trans('reports.as_of_presets.end_of_last_month')],
            ['date' => '2026-06-30', 'label' => trans('reports.as_of_presets.end_of_this_financial_quarter')],
            ['date' => '2026-03-31', 'label' => trans('reports.as_of_presets.end_of_last_financial_quarter')],
            ['date' => '2027-03-31', 'label' => trans('reports.as_of_presets.end_of_this_financial_year')],
            ['date' => '2026-03-31', 'label' => trans('reports.as_of_presets.end_of_last_financial_year')],
        ], $as_of['presets']);

        // The report reads the end of that day, else of today
        $report = new ProfitLoss(Report::where('class', ProfitLoss::class)->first(), false);

        foreach (['as_of:2026-06-30' => '2026-06-30 23:59:59', 'as_of:yesterday' => '2026-04-14 23:59:59', 'as_of:junk' => '2026-04-15 23:59:59', '' => '2026-04-15 23:59:59'] as $search => $date) {
            $this->setRequest(['search' => $search]);

            $this->assertSame($date, $report->getAsOfDate()->toDateTimeString(), $search);
        }
    }

    /**
     * The bar's definition for a report page with this query, after an optional change to the report's chips.
     */
    protected function getControls(string $class, array $query, ?callable $prepare = null): array
    {
        $model = Report::where('class', $class)->first();

        $this->setRequest($query);

        /** @var ReportClass $report */
        $report = new $class($model);

        if ($prepare) {
            $prepare($report);
        }

        return $report->getFilterControls();
    }

    /**
     * Bind a report page request with this query.
     */
    protected function setRequest(array $query = []): void
    {
        app()->instance('request', Request::create('/' . company_id() . '/common/reports', 'GET', $query));

        Facade::clearResolvedInstance('request');
    }
}
