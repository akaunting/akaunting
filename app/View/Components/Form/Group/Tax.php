<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;
use App\Models\Setting\Tax as Model;

class Tax extends Form
{
    public $type = 'tax';

    public $path;

    public $field;

    public $taxes;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        if (empty($this->name)) {
            $this->name = 'tax_id';
        }

        $this->path = route('modals.taxes.create');

        $this->field = [
            'key' => 'id',
            'value' => 'name'
        ];

        $this->taxes = Model::enabled()->orderBy('name')->pluck('name', 'id');

        $tax_id = old('tax.id', old('tax_id', null));

        if (! empty($tax_id)) {
            $this->selected = $tax_id;

            if ($this->taxes->has($tax_id)) {
                $tax = Model::find($tax_id);

                $this->taxes->put($tax->id, $tax->name);
            }
        }

        if (empty($this->selected)) {
            $this->selected = setting('default.tax');
        }

        return view('components.form.group.tax');
    }
}
