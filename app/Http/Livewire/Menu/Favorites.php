<?php

namespace App\Http\Livewire\Menu;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class Favorites extends Component
{
    public $favorites = [];

    protected $listeners = [
        'addedFavorite' => 'render',
        'removedFavorite' => 'render',
    ];

    public function render(): View
    {
        $this->favorites = collect();

        $favorites = setting('favorites.menu.' . user()->id, []);

        if (!empty($favorites)) {
            $favorites = json_decode($favorites, true);

            foreach ($favorites as $favorite) {
                $favorite['active'] = false;

                try {
                    $favorite['url'] = $this->getUrl($favorite);
                } catch (\Exception $e) {
                    continue;
                }

                $favorite['id'] = $this->getFavoriteId($favorite);

                if ($this->isActive($favorite['url'])) {
                    $favorite['active'] = true;
                }

                $this->favorites->push($favorite);
            }
        }

        return view('livewire.menu.favorites');
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl($favorite)
    {
        if (! empty($favorite['route'])) {
            $route = $favorite['route'];

            if (is_array($route)) {
                $url = route($route[0], $route[1]);
            } else {
                $url = route($route);
            }

            return str_replace(url('/') . '/', '', $url);
        }

        if (empty($favorite['url'])) {
            return '/#';
        }

        return str_replace(url('/') . '/', '', url($favorite['url']));
    }

    /**
     * Get active state for current item.
     *
     * @return mixed
     */
    public function isActive($url)
    {
        if (empty($url) || in_array($url, ['/'])) {
            return Request::is($url);
        } else {
            return Request::is($url, $url . '/*');
        }
    }

    public function getFavoriteId($favorite)
    {
        $id = Str::of($favorite['url'])
                ->replace(url('/'), '-')
                ->replace(company_id(), '')
                ->replace(['/', '?', '='], '-')
                ->trim('-')
                ->squish();

        return 'menu-favorites-' . $id;
    }
}
