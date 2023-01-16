<?php

namespace App\Http\Livewire\Menu;

use App\Events\Menu\NewwCreated;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

class Neww extends Component
{
    public $user = null;

    public $keyword = '';

    public $neww = [];

    protected $listeners = ['resetKeyword'];

    public function render(): View
    {
        $this->user = user();

        menu()->create('neww', function ($menu) {
            $menu->style('tailwind');

            event(new NewwCreated($menu));

            foreach($menu->getItems() as $item) {
                if ($this->availableInSearch($item)) {
                    $this->neww[] = $item;

                    continue;
                }

                $menu->removeByTitle($item->title);
            }
        });

        return view('livewire.menu.neww');
    }

    public function availableInSearch($item): bool
    {
        if (empty($this->keyword)) {
            return true;
        }

        return $this->search($item);
    }

    public function search($item): bool
    {
        $status = false;

        $keywords = explode(' ', $this->keyword);

        foreach ($keywords as $keyword) {
            if (Str::contains(Str::lower($item->title), Str::lower($keyword))) {
                $status = true;

                break;
            }

            if (
                !empty($item->attributes['search_keywords'])
                && Str::contains(Str::lower($item->attributes['search_keywords']), Str::lower($keyword))
            ) {
                $status = true;

                break;
            }
        }

        return $status;
    }

    public function resetKeyword(): void
    {
        $this->keyword = '';
    }
}
