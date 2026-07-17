<?php

namespace App\Http\Livewire\Report;

use App\Models\Common\Report;
use App\Utilities\Reports as Utility;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;

class Pins extends Component
{
    public $pins = [];

    public $categories;

    public $reports = [];

    public $icons = [];

    public $report_id = null;

    protected $listeners = [
        'addedPin' => 'render',
        'removedPin' => 'render',
    ];

    public function render(): View
    {
        $this->reports = collect();

        $pins = setting('favorites.report.' . user()->id, []);

        if (!empty($pins)) {
            $pins = json_decode($pins, true);

            foreach ($this->categories as $category) {
                foreach($category['reports'] as $report) {
                    if (is_array($report)) {
                        $report = Report::find($report['id']);
                    }

                    // Only pinned reports are displayed, so skip everything else before the
                    // expensive canShow()/getClassInstance() work — instantiating all 150+
                    // reports on render was causing 256s timeouts. Pins are capped at 6.
                    if (empty($report) || ! in_array($report->id, $pins)) {
                        continue;
                    }

                    if (! Utility::canShow($report->class)) {
                        continue;
                    }

                    $class = Utility::getClassInstance($report, false);

                    if (empty($class)) {
                        continue;
                    }

                    $this->reports->push($report);

                    $this->icons[$report->id] = $class->getIcon();
                }
            }
        }

        return view('livewire.report.pins');
    }
}
