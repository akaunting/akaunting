<?php

namespace App\Abstracts\View\Components;

use App\Abstracts\View\Component;
use Illuminate\Support\Collection;

abstract class Form extends Component
{
    /** @var string */
    public $type;

    /** @var string */
    public $name;

    /** @var string */
    public $label;

    /** @var string */
    public $id;

    public $value;

    /** @var string */
    public $valueKey;

    /** @var string */
    public $placeholder;

    /** @var string */
    public $searchText;

    /** @var array */
    public $options;

    /** @var array */
    public $option;

    /** @var string */
    public $optionKey;

    /** @var string */
    public $optionValue;

    /** @var array */
    public $fullOptions;

    /** @var string */
    public $checked;

    /** @var string */
    public $checkedKey;

    /** @var string */
    public $selected;

    /** @var string */
    public $selectedKey;

    /** @var string */
    public $rows;

    /** @var bool */
    public $remote;

    /** @var bool */
    public $multiple;

    /** @var bool */
    public $addNew;

    /** @var bool */
    public $group;

    /** @var bool */
    public $searchable;

    /** @var bool */
    public $disabled;

    /** @var bool */
    public $readonly;

    /** @var bool */
    public $required;

    /** @var bool */
    public $notRequired;

    /** @var string */
    public $formGroupClass = 'sm:col-span-3';

    /** @var string */
    public $inputGroupClass = '';

    /** @var array */
    public $custom_attributes = [];

    /** @var array */
    public $dynamicAttributes = [];

    /** @var bool */
    public $hideCurrency;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $name = '', string $type = 'text', string $label = '', string $id = null, $value = '', $valueKey = null, string $placeholder = '', string $searchText = '',
        $options = [], $option = [], string $optionKey = 'id', string $optionValue = 'name', $fullOptions = [], $checked = null, $checkedKey = null, $selected = null, $selectedKey = null, $rows = '3',
        $remote = false, $multiple = false, $addNew = false, $group = false,
        bool $searchable = false, bool $disabled = false, bool $readonly = false, bool $required = true, bool $notRequired = false,
        string $formGroupClass = '', string $inputGroupClass = '',
        $dynamicAttributes = '',
        bool $hideCurrency = false
    ) {
        $this->type = $this->getType($type);
        $this->name = $this->getName($name);
        $this->label = $label;
        $this->id = $id ?? $name;
        $this->value = $this->getValue($value, $valueKey);
        $this->placeholder = $this->getPlaceholder($placeholder);
        $this->searchText = $this->getSearchText($searchText);
        $this->rows = $rows;

        $this->remote = $remote;
        $this->multiple = $multiple;
        $this->addNew = $addNew;
        $this->group = $group;

        $this->searchable = $searchable;
        $this->disabled = $disabled;
        $this->readonly = $readonly;
        $this->required = $this->getRequired($required, $notRequired);

        $this->options = $this->getOptions($options);
        $this->option = $option;
        $this->optionKey = $optionKey;
        $this->optionValue = $optionValue;
        $this->fullOptions = $this->getFullOptions($fullOptions, $options, $searchable);
        $this->checked = $this->getChecked($checked, $checkedKey);
        $this->selected = $this->getSelected($selected, $selectedKey);

        $this->formGroupClass = $this->getFromGroupClass($formGroupClass);
        $this->inputGroupClass = $this->getInputGroupClass($inputGroupClass);

        $this->custom_attributes = $this->getCustomAttributes();

        $this->setDynamicAttributes($dynamicAttributes);

        $this->hideCurrency = $hideCurrency;
    }

    protected function getType($type)
    {
        if (! empty($type) && (! empty($this->type) && $type != 'text')) {
            return $type;
        }

        if (! empty($this->type)) {
            return $this->type;
        }
    }

    protected function getName($name)
    {
        if (! empty($name)) {
            return $name;
        }

        return $this->name;
    }

    protected function getValue($value, $valueKey)
    {
        if ($value != '') {
            return $value;
        }

        if (empty($valueKey)) {
            $valueKey = $this->name;
        }

        if (empty($valueKey)) {
            return '';
        }

        // set model value.
        $model = $this->getParentData('model');

        $value_keys = explode('.', $valueKey);

        if (count($value_keys) > 1) {
            $valueKey = $value_keys[0];
        }

        if (! empty($model->{$valueKey})) {
            $value = $model->{$valueKey};
        }

        if ($model instanceof Collection) {
            $value = $model->get($valueKey);
        }

        if (count($value_keys) > 1) {
            $value = $value[0]->{$value_keys[1]};
        }

        if (empty($value) && request()->has($valueKey)) {
            $value = request()->get($valueKey);
        }

        return old($valueKey, $value);
    }

    protected function getPlaceholder($placeholder)
    {
        if (! empty($placeholder)) {
            return $placeholder;
        }

        $label = $this->label;

        if (! empty($this->label) && ! empty($this->label->contents)) {
            $label = $this->name;
        }

        if ($this->type == 'select') {
            return trans('general.form.select.field', ['field' => $label]);
        }

        return trans('general.form.enter', ['field' => $label]);
    }

    protected function getSearchText($searchText)
    {
        if (! empty($searchText)) {
            return $searchText;
        }

        return trans('general.search_placeholder');
    }

    protected function getOptions($options)
    {
        if (! empty($options)) {
            if (is_array($options) && ! $this->group) {
                $o = collect();

                foreach ($options as $key => $value) {
                    if (is_array($value)) {
                        $o->push((object) $value);
                    } else {
                        $o->push((object) [
                            'id' => $key,
                            'name' => $value,
                        ]);
                    }
                }

                $options = $o;
            }

            return $options;
        }

        return [];
    }

    protected function getFullOptions($fullOptions, $options, $searchable)
    {
        if (! empty($fullOptions)) {
            return $fullOptions;
        }

        if ($searchable && empty($fullOptions)) {
            $this->options = $this->options->take(setting('default.select_limit'));

            return $options;
        }

        return [];
    }

    protected function getChecked($checked, $checkedKey)
    {
        return $this->getValue($checked, $checkedKey);
    }

    protected function getSelected($selected, $selectedKey)
    {
        return $this->getValue($selected, $selectedKey);
    }

    protected function getRequired($required, $notRequired)
    {
        if (! empty($notRequired)) {
            return false;
        }

        return $required;
    }

    protected function getFromGroupClass($formGroupClass)
    {
        if (! empty($formGroupClass)) {
            return $formGroupClass;
        }

        return $this->formGroupClass;
    }

    protected function getInputGroupClass($inputGroupClass)
    {
        if (! empty($inputGroupClass)) {
            return $inputGroupClass;
        }

        return $this->inputGroupClass;
    }

    protected function getCustomAttributes()
    {
        $attributes = [];

        if (! empty($this->required)) {
            $attributes['required'] = $this->required;
        }

        if (! empty($this->disabled)) {
            $attributes['disabled'] = $this->disabled;
        }

        if (! empty($this->readonly)) {
            $attributes['readonly'] = $this->readonly;
        }

        foreach ($this->custom_attributes as $key => $value) {
            $attributes[$key] = $value;
        }

        return $attributes;
    }

    protected function setDynamicAttributes($dynamicAttributes)
    {
        if (! empty($dynamicAttributes)) {
            $this->dynamicAttributes = $dynamicAttributes;
        }
    }
}
