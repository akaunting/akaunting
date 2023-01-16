<?php

namespace App\Http\Livewire\Menu;

use App\Events\Menu\SettingsCreated;
use App\Models\Module\Module;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

class Settings extends Component
{
    public $user = null;

    public $keyword = '';

    public $settings = [];

    public $active_menu = 0;

    protected $listeners = ['resetKeyword'];

    public function render(): View
    {
        $this->user = user();

        menu()->create('settings', function ($menu) {
            $menu->style('tailwind');

            event(new SettingsCreated($menu));

            $this->addSettingsOfModulesFromJsonFile($menu);

            foreach($menu->getItems() as $item) {
                if ($item->isActive()) {
                    $this->active_menu = 1;
                }

                if ($this->availableInSearch($item)) {
                    $this->settings[] = $item;

                    continue;
                }

                $menu->removeByTitle($item->title);
            }
        });

        return view('livewire.menu.settings');
    }

    public function addSettingsOfModulesFromJsonFile($menu): void
    {
        // Get enabled modules
        $enabled_modules = Module::enabled()->get();

        $order = 110;

        foreach ($enabled_modules as $module) {
            $m = module($module->alias);

            // Check if the module exists and has settings
            if (!$m || empty($m->get('settings'))) {
                continue;
            }

            if ($this->user->cannot('read-' . $m->getAlias() . '-settings')) {
                continue;
            }

            $menu->route('settings.module.edit', $m->getName(), ['alias' => $m->getAlias()], $m->get('setting_order', $order), [
                'icon' => $m->get('icon', 'custom-akaunting'),
                'search_keywords' => $m->getDescription(),
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
