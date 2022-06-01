<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;
use App\Models\Setting\Currency as Model;

class Currency extends Form
{
    public $type = 'currency';

    public $path;

    public $field;

    public $currencies;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        if (empty($this->name)) {
            $this->name = 'currency_code';
        }

        $this->path = route('modals.currencies.create');

        $this->field = [
            'key' => 'code',
            'value' => 'name'
        ];

        $this->currencies = Model::enabled()->orderBy('name')->pluck('name', 'code');

        if (empty($this->selected) && empty($this->getParentData('model'))) {
            $this->selected = setting('default.currency');
        }

        return view('components.form.group.currency');
    }
}
