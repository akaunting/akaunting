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

    /** @var string */
    public $textAddContact;

    /** @var string */
    public $textCreateNewContact;

    /** @var string */
    public $textEditContact;

    /** @var string */
    public $textContactInfo;

    /** @var string */
    public $textChooseDifferentContact;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $type, $contact = false, $contacts = [], $search_route = '', $create_route = '',
        string $textAddContact = '', string $textCreateNewContact = '', string $textEditContact = '', string $textContactInfo = '', string $textChooseDifferentContact = ''
    )
    {
        $this->type = $type;
        $this->contact = $contact;
        $this->contacts = $contacts;
        $this->search_route = $search_route;
        $this->create_route = $create_route;

        $this->textAddContact = $this->getTextAddContact($type, $textAddContact);
        $this->textCreateNewContact = $this->getTextCreateNewContact($type, $textCreateNewContact);
        $this->textEditContact = $this->getTextEditContact($type, $textEditContact);
        $this->textContactInfo = $this->getTextContactInfo($type, $textContactInfo);
        $this->textChooseDifferentContact = $this->getTextChooseDifferentContact($type, $textChooseDifferentContact);
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
    
    protected function getTextAddContact($type, $textAddContact)
    {
        if (!empty($textAddContact)) {
            return $textAddContact;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textAddContact = 'vendor';
                break;
            default:
                $textAddContact = 'customer';
                break;
        }

        return $textAddContact;
    }

    protected function getTextCreateNewContact($type, $textCreateNewContact)
    {
        if (!empty($textCreateNewContact)) {
            return $textCreateNewContact;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textCreateNewContact = 'vendor';
                break;
            default:
                $textCreateNewContact = 'customer';
                break;
        }

        return $textCreateNewContact;
    }

    protected function getTextEditContact($type, $textEditContact)
    {
        if (!empty($textEditContact)) {
            return $textEditContact;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textEditContact = 'vendor';
                break;
            default:
                $textEditContact = 'customer';
                break;
        }

        return $textEditContact;
    }

    protected function getTextContactInfo($type, $textContactInfo)
    {
        if (!empty($textContactInfo)) {
            return $textContactInfo;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textContactInfo = 'vendor';
                break;
            default:
                $textContactInfo = 'customer';
                break;
        }

        return $textContactInfo;
    }

    protected function getTextChooseDifferentContact($type, $textChooseDifferentContact)
    {
        if (!empty($textChooseDifferentContact)) {
            return $textChooseDifferentContact;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $textChooseDifferentContact = 'vendor';
                break;
            default:
                $textChooseDifferentContact = 'customer';
                break;
        }

        return $textChooseDifferentContact;
    }
}
