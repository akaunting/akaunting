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

    /** @var $error  */
    public $error;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $type, $contact = false, $contacts = [], $search_route = '', $create_route = '', string $error = '',
        $textAddContact = '', $textCreateNewContact = '', $textEditContact = '', $textContactInfo = '', $textChooseDifferentContact = ''
    )
    {
        $this->type = $type;
        $this->contact = $contact;
        $this->contacts = $contacts;
        $this->search_route = $search_route;
        $this->create_route = $create_route;
        $this->error = ($error) ?: "form.errors.get('contact_id')" ;

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
        $this->placeholder = trans('general.placeholder.contact_search', ['type' => trans_choice('general.' . Str::plural($this->type, 2), 1)]);

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
                $textAddContact = [
                    'general.form.add',
                    'general.vendors'
                ];
                break;
            default:
                $textAddContact = [
                    'general.form.add',
                    'general.customers'
                ];
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
                $textCreateNewContact = [
                    'general.form.add_new',
                    'general.vendors'
                ];
                break;
            default:
                $textCreateNewContact = [
                    'general.form.add_new',
                    'general.customers'
                ];
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
                $textEditContact = [
                    'general.form.contact_edit',
                    'general.vendors'
                ];
                break;
            default:
                $textEditContact = [
                    'general.form.contact_edit',
                    'general.customers'
                ];
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
                $textContactInfo = 'bills.bill_from';
                break;
            default:
                $textContactInfo = 'invoices.bill_to';
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
                $textChooseDifferentContact = [
                    'general.form.choose_different',
                    'general.vendors'
                ];
                break;
            default:
                $textChooseDifferentContact = [
                    'general.form.choose_different',
                    'general.customers'
                ];
                break;
        }

        return $textChooseDifferentContact;
    }
}
