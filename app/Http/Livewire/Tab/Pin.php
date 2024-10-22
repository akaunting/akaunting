<?php

namespace App\Http\Livewire\Tab;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Pin extends Component
{
    public $id;

    public $type;

    public $tab;

    public $pinned = false;

    protected $listeners = [
        'addedPin' => 'render',
        'removedPin' => 'render',
    ];

    public function render(): View
    {
        $this->pinned = false;

        $pins = setting('favorites.tab.' . user()->id, []);

        if (! empty($pins) && ! $this->pinned) {
            $pins = json_decode($pins, true);
            $type_pinned = $pins[$this->type] ?? null;

            if (isset($pins[$this->type]) && $this->tab == $type_pinned) {
                $this->pinned = true;
            }
        }

        return view('livewire.tab.pin');
    }

    public function changeStatus($tab)
    {
        if ($this->pinned) {
            $this->removePin($tab);
        } else {
            $this->addPin($tab);
        }
    }

    public function addPin($tab)
    {
        $pins = setting('favorites.tab.' . user()->id, []);

        if (!empty($pins)) {
            $pins = json_decode($pins, true);
        }

        $type_pinned = $pins[$this->type] ?? null;

        if ($this->tab == $type_pinned) {
            return;
        }

        $pins[$this->type] = $tab;

        $this->pinned = true;

        setting(['favorites.tab.' . user()->id => json_encode($pins)])->save();

        $this->dispatch('addedPin');
    }

    public function removePin($tab)
    {
        $pins = setting('favorites.tab.' . user()->id, []);

        if (!empty($pins)) {
            $pins = json_decode($pins, true);
        }

        foreach ($pins as $key => $pinned_id) {
            if ($pinned_id != $tab) {
                continue;
            }

            unset($pins[$key]);
            $this->pinned = false;

            break;
        }

        setting(['favorites.tab.' . user()->id => json_encode($pins)])->save();

        $this->dispatch('removedPin');
    }
}
