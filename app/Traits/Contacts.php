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
}
