<?php

namespace App\Http\Livewire\Menu;

use App\Events\Menu\SettingsCreated;
use App\Events\Menu\SettingsCreating;
use App\Events\Menu\SettingsFinished;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

class Settings extends Component
{
    public $user = null;

    public $keyword = '';

    public $active_menu = 0;

    protected $listeners = [
        'resetKeyword',
    ];

    public function render(): View
    {
        $this->user = user();

        menu()->create('settings', function ($menu) {
            $menu->style('tailwind');

            event(new SettingsCreating($menu));
            event(new SettingsCreated($menu));

            $this->addSettingsOfModulesFromJsonFile($menu);

            foreach($menu->getItems() as $item) {
                if ($item->isActive()) {
                    $this->active_menu = 1;
                }

                if ($this->availableInSearch($item)) {
                    continue;
                }

                $menu->removeByTitle($item->title);
            }

            #todo event name must be changed to SettingsCreated
            event(new SettingsFinished($menu));
        });

        return view('livewire.menu.settings');
    }

    public function addSettingsOfModulesFromJsonFile($menu): void
    {
        // Get enabled modules
        $enabled_modules = module()->allEnabled();

        $order = 110;

        foreach ($enabled_modules as $module) {
            // Check if the module has settings
            if (empty($module->get('settings'))) {
                continue;
            }

            if ($this->user->cannot('read-' . $module->getAlias() . '-settings')) {
                continue;
            }

            $menu->route('settings.module.edit', $module->getName(), ['alias' => $module->getAlias()], $module->get('setting_order', $order), [
                'icon' => $module->get('icon', 'custom-akaunting'),
                'search_keywords' => $module->getDescription(),
            ]);

            $order += 10;
        }
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
