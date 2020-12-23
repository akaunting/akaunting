<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;
use App\Models\Common\Contact;

class SelectContactCard extends Component
{
    public $type;

    public $contact;

    public $placeholder;

    public $contacts;

    public $search_route;

    public $create_route;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type, $contact = false, $contacts = [], $search_route = '', $create_route = '')
    {
        $this->type = $type;
        $this->contact = $contact;
        $this->contacts = $contacts;
        $this->search_route = $search_route;
        $this->create_route = $create_route;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        if (empty($this->contacts)) {
            $this->contacts = Contact::{$this->type}()->enabled()->orderBy('name')->take(setting('default.select_limit'))->get();
        }

        if (empty($this->search_route)) {
            switch ($this->type) {
                case 'customer':
                    $this->search_route = route('customers.index');
                    break;
                case 'vendor':
                    $this->search_route = route('vendors.index');
                    break;
            }
        }

        if (empty($this->create_route)) {
            switch ($this->type) {
                case 'customer':
                    $this->create_route = route('modals.customers.create');
                    break;
                case 'vendor':
                    $this->create_route = route('modals.vendors.create');
                    break;
            }
        }

        #todo  3rd part apps
        $this->placeholder = trans('general.placeholder.contact_search', ['type' => $this->type]);

        return view('components.select-contact-card');
    }
}
