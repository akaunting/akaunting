<?php

namespace App\Traits;

trait Contacts
{
    public function isCustomer()
    {
        $type = $this->type ?? $this->contact->type ?? $this->model->type ?? 'customer';

        return in_array($type, $this->getCustomerTypes());
    }

    public function isVendor()
    {
        $type = $this->type ?? $this->contact->type ?? $this->model->type ?? 'vendor';

        return in_array($type, $this->getVendorTypes());
    }

    public function isEmployee()
    {
        $type = $this->type ?? $this->contact->type ?? $this->model->type ?? 'employee';

        return in_array($type, $this->getEmployeeTypes());
    }

    public function getCustomerTypes($return = 'array')
    {
        return $this->getContactTypes('customer', $return);
    }

    public function getVendorTypes($return = 'array')
    {
        return $this->getContactTypes('vendor', $return);
    }

    public function getEmployeeTypes($return = 'array')
    {
        return $this->getContactTypes('employee', $return);
    }

    public function getContactTypes($index, $return = 'array')
    {
        $types = (string) setting('contact.type.' . $index);

        return ($return == 'array') ? explode(',', $types) : $types;
    }

    public function addCustomerType($new_type)
    {
        $this->addContactType($new_type, 'customer');
    }

    public function addVendorType($new_type)
    {
        $this->addContactType($new_type, 'vendor');
    }

    public function addEmployeeType($new_type)
    {
        $this->addContactType($new_type, 'employee');
    }

    public function addContactType($new_type, $index)
    {
        $types = explode(',', setting('contact.type.' . $index));

        if (in_array($new_type, $types)) {
            return;
        }

        $types[] = $new_type;

        setting([
            'contact.type.' . $index => implode(',', $types),
        ])->save();
    }

    public function getFormattedAddress($city = null, $country = null, $state = null, $zip_code = null)
    {
        if (is_null($city)
            && is_null($country)
            && is_null($state)
            && is_null($zip_code)
        ) {
            return null;
        }

        $address_format = setting('default.address_format');

        $formatted_address = str_replace(
            ["{city}", "{country}", "{state}", "{zip_code}", "\n"],
            [$city, $country, $state, $zip_code, '<br>'],
            $address_format
        );

        return $formatted_address;
    }
}
