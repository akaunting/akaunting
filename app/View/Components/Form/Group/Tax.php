<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;
use App\Models\Setting\Tax as Model;

class Tax extends Form
{
    public $type = 'tax';

    public $path;

    public $remoteAction;

    public $field;

    public $taxes;

    public $currency;

    public $change;

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
        $this->remoteAction = route('taxes.index', ['search' => 'enabled:1']);

        $this->field = [
            'key' => 'id',
            'value' => 'title'
        ];

        $this->taxes = Model::enabled()->orderBy('name')->get();

        $model = $this->getParentData('model');

        $tax_id = old('tax_ids', old('tax.id', old('tax_id', null)));

        if (! empty($tax_id)) {
            $this->selected = ($this->multiple) ? (array) $tax_id : $tax_id;

            foreach ($tax_id as $id) {
                if (! $this->taxes->has($id)) {
                    $tax = Model::find($id);

                    $this->taxes->put($tax->id, $tax->title);
                }
            }
        }

        if (! empty($model) && ! empty($model->{$this->name})) {
            $this->selected = $model->{$this->name};
        }

        if ($this->selected === null && $this->multiple) {
            $this->selected = (setting('default.tax')) ? [setting('default.tax')] : null;
        } else if ($this->selected === null) {
            $this->selected = setting('default.tax', null);
        }

        return view('components.form.group.tax');
    }
}
