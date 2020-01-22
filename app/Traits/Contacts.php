<?php

namespace App\Traits;

trait Contacts
{
    public function getCustomerTypes($return = 'array')
    {
        $types = (string) setting('contact.type.customer', 'customer');

        return ($return == 'array') ? explode(',', $types) : $types;
    }

    public function getVendorTypes($return = 'array')
    {
        $types = (string) setting('contact.type.vendor', 'vendor');

        return ($return == 'array') ? explode(',', $types) : $types;
    }

    public function setCustomerType($new_type)
    {
        $this->setType($new_type, 'customer');
    }

    public function setVendorType($new_type)
    {
        $this->setType($new_type, 'vendor');
    }

    public function setType($new_type, $index)
    {
        $types = explode(',', setting('contact.type.' . $index, $index));

        if (in_array($new_type, $types)) {
            return;
        }

        $types[] = $new_type;

        setting([
            'contact.type.' . $index => implode(',', $types),
        ])->save();
    }
}
