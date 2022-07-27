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

        $this->setRoutes();

        $this->label = trans_choice('general.' . Str::plural($this->type), 1);

        $this->contacts = Model::type($this->type)->enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');

        $model = $this->getParentData('model');

        if (! empty($model)) {
            $this->selected = $model->contact_id;

            if (! $this->contacts->has($model->contact_id) && ($contact = $model->contact)) {
                $this->contacts->put($contact->id, $contact->name);
            }
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
