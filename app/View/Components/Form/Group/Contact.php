<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;
use App\Models\Common\Contact as Model;
use Illuminate\Support\Str;

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
        if (empty($this->name)) {
            $this->name = 'contact_id';
        }

        if (empty($this->label)) {
            $this->label = trans_choice('general.' . Str::plural($this->type), 1);
        }

        if (empty($this->contacts) && ! empty($this->options)) {
            $this->contacts = $this->options;
        } else if (empty($this->contacts)) {
            $this->contacts = Model::type($this->type)->enabled()->orderBy('name')->take(setting('default.select_limit'))->get();
        }

        $this->setRoutes();

        $model = $this->getParentData('model');

        $contact_id = old('contact.id', old('contact_id', null));

        if (! empty($contact_id)) {
            $this->selected = $contact_id;

            if (! $this->contacts->has($contact_id)) {
                $contact = Model::find($contact_id);

                $this->contacts->push($contact);
            }
        }

        if (! empty($model) && ! empty($model->contact_id)) {
            $this->selected = $model->contact_id;

            $selected_contact = $model->contact;
        }

        if (! empty($selected_contact) && ! $this->contacts->has($selected_contact->id)) {
            $this->contacts->push($selected_contact);
        }

        return view($this->view);
    }

    public function setRoutes(): void
    {
        $alias = config('type.contact.' . $this->type . '.alias');
        $prefix = config('type.contact.' . $this->type . '.route.prefix');

        $parameters = ['search' => 'enabled:1'];

        $this->path = ! empty($alias) ? route("{$alias}.modals.{$prefix}.create") : route("modals.{$prefix}.create");
        $this->remoteAction = ! empty($alias) ? route("{$alias}.{$prefix}.index", $parameters) : route("{$prefix}.index", $parameters);
    }
}
