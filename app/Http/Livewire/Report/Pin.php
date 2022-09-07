<?php

namespace App\Http\Livewire\Report;

use App\Models\Common\Report;
use App\Utilities\Reports as Utility;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Pin extends Component
{
    public $categories;

    public $pinned = false;

    public $report;

    public function render(): View
    {
        $pins = setting('favorites.report.' . user()->id, []);

        if (!empty($pins)) {
            $pins = json_decode($pins, true);

            foreach ($this->categories as $category) {
                foreach($category['reports'] as $report) {
                    if (is_array($report)) {
                        $report = Report::find($report['id']);
                    }

                    if (! Utility::canShow($report->class)) {
                        continue;
                    }

                    $class = Utility::getClassInstance($report, false);

                    if (empty($class)) {
                        continue;
                    }

                    if (in_array($this->report->id, $pins)) {
                        $this->pinned = true;

                        break;
                    }
                }
            }
        }

        return view('livewire.report.pin');
    }

    public function changeStatus($report_id)
    {
        if ($this->pinned) {
            $this->removePin($report_id);
        } else {
            $this->addPin($report_id);
        }
    }

    public function addPin($report_id)
    {
        $pins = setting('favorites.report.' . user()->id, []);

        if (!empty($pins)) {
            $pins = json_decode($pins, true);
        }

        if (in_array($report_id, $pins)) {
            return;
        }

        if (count($pins) >= 6) {
            return;
        }

        $pins[] = $report_id;

        $this->pinned = true;

        setting(['favorites.report.' . user()->id => json_encode($pins)])->save();

        $this->emit('addedPin');
    }

    public function removePin($report_id)
    {
        $pins = setting('favorites.report.' . user()->id, []);

        if (!empty($pins)) {
            $pins = json_decode($pins, true);
        }

        foreach ($pins as $key => $pinned_id) {
            if ($pinned_id != $report_id) {
                continue;
            }

            unset($pins[$key]);
            $this->pinned = false;

            break;
        }

        setting(['favorites.report.' . user()->id => json_encode($pins)])->save();

        $this->emit('removedPin');
    }
}
