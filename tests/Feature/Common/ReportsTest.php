<?php

namespace Tests\Feature\Common;

use App\Events\Report\DataLoading;
use App\Jobs\Auth\CreateUser;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Common\CreateCompany;
use App\Jobs\Common\UpdateReport;
use App\Models\Banking\Transaction;
use App\Models\Common\Company;
use App\Models\Common\Report;
use App\Reports\ProfitLoss;
use App\Traits\Permissions;
use App\Utilities\Date;
use Illuminate\Support\Facades\Event;
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
