<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;
use App\Models\Common\Contact as Model;

class Contact extends Form
{
    public $type = 'contact';

    public $label;

    public $view = 'components.form.group.contact';

    public $path;

    public $remoteAction;

    public $contacts;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $type = $this->type;

        switch ($type) {
            case 'customer':
                $this->prepareCustomer();
                break;
            case 'vendor':
                $this->prepareVendor();
                break;
        }

        return view($this->view);
    }

    protected function prepareCustomer()
    {
        if (empty($this->name)) {
            $this->name = 'contact_id';
        }

        $this->path = route('modals.customers.create');
        $this->remoteAction = route('customers.index');
        $this->label = trans_choice('general.customers', 1);

        $this->contacts = Model::customer()->enabled()->orderBy('name')->take(setting('default.select_limit'))->get();

        if (! empty($model) && $model->customer && ! $this->contacts->has($model->contact_id)) {
            $this->contacts->put($model->customer->id, $model->customer->name);
        }
    }

    protected function prepareVendor()
    {
        if (empty($this->name)) {
            $this->name = 'contact_id';
        }

        $this->path = route('modals.vendors.create');
        $this->remoteAction = route('vendors.index');
        $this->label = trans_choice('general.vendors', 1);

        $this->contacts = Model::vendor()->enabled()->orderBy('name')->take(setting('default.select_limit'))->get();

        if (! empty($model) && $model->vendor && ! $this->contacts->has($model->contact_id)) {
            $this->contacts->put($model->vendor->id, $model->vendor->name);
        }
    }
}
