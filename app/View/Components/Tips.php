<?php

namespace App\View\Components;

use App\Abstracts\View\Component;
use App\Traits\Modules;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class Tips extends Component
{
    use Modules;

    /** @var string */
    public $position;

    /** @var string */
    public $path;

    /** @var objcet */
    public $tips;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $position = 'relative',
        string $path = null,
        $tips = []
    ) {
        $this->position = $position;
        $this->path = $path;
        $this->tips = collect();

        $this->setTips($tips);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        switch ($this->position) {
            case 'fixed':
                $view = 'components.tips.fixed';
                break;
            default:
                $view = 'components.tips.relative';
        }

        if ($this->tips->count() > 1) {
            $view = 'components.tips.relative';
        }

        return view($view);
    }

    protected function setTips($tips)
    {
        if (! empty($tips)) {
            $this->tips = $tips;
        }

        if (! $path = Route::current()->uri()) {
            return;
        }

        $path = Str::replace('{company_id}/', '', $path);

        if (! $tips = $this->getTips($path)) {
            return;
        }

        $rows = collect();

        shuffle($tips);

        foreach ($tips as $tip) {
            if ($tip->position != $this->position) {
                continue;
            }

            if (! empty($tip->alias) && $this->moduleIsEnabled($tip->alias)) {
                continue;
            }

            if (Str::contains($tip->action, '{company_id}')) {
                $tip->action = Str::replace('{company_id}', company_id(), $tip->action);
            }

            $rows->push($tip);
        }

        if ($rows->count()) {
            $row = $rows->shuffle()->first();

            $this->tips->push($row);
        }
    }
}
