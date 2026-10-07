<?php

namespace Tests\Feature\Common;

use App\Abstracts\Report as ReportClass;
use App\Jobs\Auth\CreateUser;
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
            // The dates win over a date_range: token, which core does not read; year: is dead
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

        // The free text and the date_range: token, which only DoubleEntry reads, stay word for word
        $this->assertSame(['"rent"', 'date_range:2026-01-01-to-2026-03-31'], $controls['extras']);

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
        // negated setting is read past, as getFieldValue() does; as_of:yesterday is a date to its readers
        $overdue = 'partial,sent,viewed due_at<=today';

        $controls = $this->getControls(ProfitLoss::class, [
            'search' => 'not status:' . $overdue . ' not min_amount:100 max_amount:500 not basis:cash as_of:yesterday',
        ], function (ReportClass $report) use ($overdue) {
            $report->filters['statuses'] = ['paid' => 'Paid', $overdue => 'Overdue'];
            $report->filters['min_amounts'] = [];
            $report->filters['max_amounts'] = [];
            $report->filters['as_of'] = [];
            $report->filters['keys'] = array_merge($report->filters['keys'] ?? [], [
                'statuses' => 'status',
                'min_amounts' => 'min_amount',
                'max_amounts' => 'max_amount',
                'as_of' => 'as_of',
            ]);
        });

        $filters = collect($controls['filters'])->keyBy('key');
        $strip = collect($controls['strip'])->keyBy('key');

        $this->assertSame('', $filters['status']['value']);
        $this->assertSame(['min' => '', 'max' => '500'], $filters['amount']['value']);
        $this->assertSame('cash', $strip['basis']['value']);
        $this->assertSame('2026-06-14', $strip['as_of']['value']);
        $this->assertSame(['not status:' . $overdue, 'not min_amount:100'], $controls['extras']);

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
