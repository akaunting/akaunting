<?php

namespace App\Http\Livewire\Menu;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Favorite extends Component
{
    public $title = null;

    public $icon = null;

    public $route = null;

    public $url = null;

    public $favorited = false;

    public function render(): View
    {
        $favorites = setting('favorites.menu.' . user()->id, []);

        if (!empty($favorites)) {
            $favorites = json_decode($favorites, true);

            foreach ($favorites as $favorite) {
                if ($this->title == $favorite['title']) {
                    $this->favorited = true;

                    break;
                }
            }
        }

        return view('livewire.menu.favorite');
    }

    public function changeStatus()
    {
        if ($this->favorited) {
            $this->removeFavorite();
        } else {
            $this->addFavorite();
        }
    }   

    public function addFavorite()
    {
        $favorites = setting('favorites.menu.' . user()->id, []);

        if (!empty($favorites)) {
            $favorites = json_decode($favorites, true);
        }

        /*
        if (in_array($this->title, $favorites)) {
            return;
        }
        */

        $favorites[] = [
            'title' => $this->title,
            'icon' => $this->icon,
            'route' => $this->route,
            'url' => $this->url,
        ];

        $this->favorited = true;

        setting(['favorites.menu.' . user()->id => json_encode($favorites)])->save();

        $this->dispatch('addedFavorite');
    }

    public function removeFavorite()
    {
        $favorites = setting('favorites.menu.' . user()->id, []);

        if (!empty($favorites)) {
            $favorites = json_decode($favorites, true);
        }

        foreach ($favorites as $key => $favorited) {
            if ($favorited['title'] != $this->title) {
                continue;
            }

            unset($favorites[$key]);
            $this->favorited = false;

            break;
        }

        setting(['favorites.menu.' . user()->id => json_encode($favorites)])->save();

        $this->dispatch('removedFavorite');
    }
}
