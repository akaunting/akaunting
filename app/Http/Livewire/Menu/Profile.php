<?php

namespace App\Http\Livewire\Menu;

use App\Events\Menu\ProfileCreated;
use App\Events\Menu\ProfileCreating;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Profile extends Component
{
    public $active_menu = 0;

    public function render(): View
    {
        menu()->create('profile', function ($menu) {
            $menu->style('tailwind');

            event(new ProfileCreating($menu));

            event(new ProfileCreated($menu));

            foreach($menu->getItems() as $item) {
                if ($item->isActive()) {
                    $this->active_menu = 1;
                }
            }
        });

        return view('livewire.menu.profile');
    }
}
