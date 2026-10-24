<?php

namespace App\View\Components\Accounts\Index;

use Akaunting\Apexcharts\Chart;
use App\Abstracts\View\Components\Accounts\Index as Component;
use App\Traits\Charts;
use App\Utilities\Date;

/**
 * One account on the list: what it holds, what is waiting on it, and how its balance has moved.
 *
 * Kept apart from the list that holds it, so the look of an account can change without
 * touching how the page groups and pages them. The figures are read by the list for every
 * account at once and handed in through $overview.
 */
class Account extends Component
{
    use Charts;

    /**
     * The colours of the balance line, the purple and the red of the application's palette.
     */
    public const CHART_COLOR = '#55588b';
    public const CHART_COLOR_OVERDRAWN = '#cc0000';

    /**
     * The looks of the card's actions, after the sections of a document's page: what the
     * account waits on first is green, what it waits on after that is grey, and what can always
     * be done is a link. Spelled out rather than core's kind="primary", whose margin is meant for
     * a page's head and breaks the card's row; an app adding to the card reads them from here.
     */
    public const ACTION_PRIMARY = 'relative pointer-events-auto px-3 py-1.5 rounded-xl text-sm font-medium leading-6 bg-green hover:bg-green-700 text-white';
    public const ACTION_SECONDARY = 'relative pointer-events-auto px-3 py-1.5 rounded-xl text-sm font-medium leading-6 bg-gray-100 hover:bg-gray-200';
    public const ACTION_LINK = 'relative pointer-events-auto text-sm font-medium leading-none text-purple hover:underline';

    /** @var \App\Models\Banking\Account */
    public $account;

    /** @var bool */
    public $isDefault;

    /** @var array */
    public $history;

    /** @var array|null */
    public $statement;

    /**
     * What is waiting to be reconciled and the reconciliation that takes it on, or null while
     * reconciling is turned off (AccountOverview::getReconciliation()).
     *
     * @var array|null
     */
    public $reconciliation;

    /** @var bool */
    public $isOverdrawn;

    /** @var Date */
    public $historyStart;

    /** @var Date */
    public $historyEnd;

    /** @var Chart|null */
    public $chart;

    /**
     * What an app that keeps the account up to date (bank feeds) put on the card: the bank's
     * mark, the card's green button, links beside it and a line under them about itself.
     *
     * @var string
     */
    public $appLogo;

    /** @var string */
    public $appPrimary;

    /** @var string */
    public $appLinks;

    /** @var string */
    public $appStatus;

    /**
     * Reconciling is the card's green button, or the grey one beside an app's once it has
     * taken the green.
     *
     * @var string
     */
    public $reconcileClass;

    /** @var string */
    public $linkClass = self::ACTION_LINK;

    /**
     * Whether the row of actions has anything in it; left out otherwise, so the room above the
     * line under it stays the same as on every card.
     *
     * @var bool
     */
    public $hasActions;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $account,
        array $overview = []
    ) {
        // The settings of the page, a hidden bulk action among them, from the parts above
        parent::__construct();

        $this->account = $account;
        $this->isDefault = setting('default.account') == $account->id;

        $this->history = $overview['balance_history'] ?? [];
        $this->statement = $overview['statement'] ?? null;
        $this->reconciliation = $overview['reconciliation'] ?? null;

        // A credit card is meant to be owed on, so a balance below zero is how it normally
        // stands and is not drawn as a warning the way an overdrawn bank account is
        $this->isOverdrawn = $account->type !== 'credit_card' && $account->balance < 0;

        $this->historyEnd = Date::today();
        $this->historyStart = $this->historyEnd->copy()->subDays(max(count($this->history) - 1, 0));

        $this->chart = $this->getChart();

        $this->appLogo = $this->getAppContent('account_card_logo_');
        $this->appPrimary = $this->getAppContent('account_card_primary_');
        $this->appLinks = $this->getAppContent('account_card_links_');
        $this->appStatus = $this->getAppContent('account_card_status_');

        $this->reconcileClass = $this->appPrimary === '' ? static::ACTION_PRIMARY : static::ACTION_SECONDARY;
        $this->hasActions = $this->getHasActions();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.accounts.index.account');
    }

    /**
     * What an app pushed onto one of the card's places for this account. The pushes are made
     * by the app's view composer before the list is drawn, so they are read here once.
     */
    protected function getAppContent(string $stack): string
    {
        return trim(app('view')->yieldPushContent($stack . $this->account->id));
    }

    protected function getHasActions(): bool
    {
        // An account an app keeps up to date gets its transactions from it, so it is offered
        // no statement to import (appStatus) and has nothing in the row for that
        $can_import = $this->appStatus === '' && (bool) user()?->can('create-banking-transactions');

        return $this->appPrimary !== ''
            || $this->appLinks !== ''
            || ! empty($this->reconciliation['draft_id'])
            || ! empty($this->reconciliation['count'])
            || ! empty($this->reconciliation['earlier'])
            || ! empty($this->statement)
            || $can_import;
    }

    /**
     * How the balance moved over the days, drawn the way the dashboard widgets draw theirs but
     * without axes and grid, to fit the card. Hovering a day tells its date and balance.
     */
    protected function getChart(): ?Chart
    {
        if (count($this->history) < 2) {
            return null;
        }

        $labels = [];

        foreach (array_keys($this->history) as $day) {
            $labels[] = company_date($this->historyStart->copy()->addDays($day));
        }

        $chart = new Chart();

        $chart->setType('area')
            ->setDefaultLocale($this->getDefaultLocaleOfChart())
            ->setLocales($this->getLocaleTranslationOfChart())
            ->setHeight('100%')
            ->setSparkline(['enabled' => true])
            ->setLabels($labels)
            ->setColors([$this->isOverdrawn ? static::CHART_COLOR_OVERDRAWN : static::CHART_COLOR])
            ->setDataset(trans('accounts.current_balance'), 'area', array_values($this->history))
            // The application's chart defaults show a toolbar, a grid and a dot on every point,
            // which a line this small has no room for; only the hovered day is marked
            ->setOptions([
                'chart' => [
                    'toolbar' => ['show' => false],
                    'zoom' => ['enabled' => false],
                ],
                'grid' => [
                    'show' => false,
                ],
                'markers' => [
                    'size' => 0,
                    'hover' => ['size' => 4],
                ],
                'legend' => [
                    'show' => false,
                ],
                'dataLabels' => [
                    'enabled' => false,
                ],
                'stroke' => [
                    'width' => 2,
                    'curve' => 'straight',
                ],
                'fill' => [
                    'type'      => 'solid',
                    'opacity'   => 0.12,
                ],
                // Told for the day under the pointer anywhere along the line, since there are no
                // dots left to point at
                'tooltip' => [
                    'shared' => true,
                    'intersect' => false,
                    'x' => [
                        'show' => true,
                    ],
                    'y' => [
                        'formatter' => $this->getChartLabelFormatter('money', null, $this->account->currency_code),
                    ],
                ],
            ]);

        return $chart;
    }
}
