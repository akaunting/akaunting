<?php

namespace App\Http\Livewire\Report;

use App\Utilities\Reports as Utility;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Pin extends Component
{
    public $pinned = false;

    public $report;

    public function render(): View
    {
        // Report deleted, class missing, module disabled, or access restricted — render empty
        if (
            empty($this->report)
            || ! Utility::getClassInstance($this->report, false)
            || ! Utility::canRead($this->report->class)
        ) {
            $this->pinned = false;

            return view('livewire.report.pin');
        }

        $pins = setting('favorites.report.' . user()->id, []);

        if (!empty($pins)) {
            $pins = json_decode($pins, true);

            $this->pinned = in_array($this->report->id, $pins);
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

        $this->dispatch('addedPin');
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

        $this->dispatch('removedPin');
    }
}
