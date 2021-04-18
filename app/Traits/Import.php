<?php

namespace App\Traits;

use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use App\Models\Setting\Tax;

trait Import
{
    public function getAccountId($row)
    {
        $id = isset($row['account_id']) ? $row['account_id'] : null;

        if (empty($id) && !empty($row['account_name'])) {
            $id = $this->getAccountIdFromName($row);
        }

        if (empty($id) && !empty($row['account_number'])) {
            $id = $this->getAccountIdFromNumber($row);
        }

        if (empty($id) && !empty($row['currency_code'])) {
            $id = $this->getAccountIdFromCurrency($row);
        }

        return is_null($id) ? $id : (int) $id;
    }

    public function getCategoryId($row, $type = null)
    {
        $id = isset($row['category_id']) ? $row['category_id'] : null;

        $type = !empty($type) ? $type : (!empty($row['type']) ? $row['type'] : 'income');

        if (empty($id) && !empty($row['category_name'])) {
            $id = $this->getCategoryIdFromName($row, $type);
        }

        return is_null($id) ? $id : (int) $id;
    }

    public function getContactId($row, $type = null)
    {
        $id = isset($row['contact_id']) ? $row['contact_id'] : null;

        $type = !empty($type) ? $type : (!empty($row['type']) ? (($row['type'] == 'income') ? 'customer' : 'vendor') : 'customer');

        if (empty($row['contact_id']) && !empty($row['contact_email'])) {
            $id = $this->getContactIdFromEmail($row, $type);
        }

        if (empty($id) && !empty($row['contact_name'])) {
            $id = $this->getContactIdFromName($row, $type);
        }

        return is_null($id) ? $id : (int) $id;
    }

    public function getDocumentId($row)
    {
        $id = isset($row['document_id']) ? $row['document_id'] : null;

        if (empty($id) && !empty($row['document_number'])) {
            $id = Document::number($row['document_number'])->pluck('id')->first();
        }

        if (empty($id) && !empty($row['invoice_number'])) {
            $id = Document::invoice()->number($row['invoice_number'])->pluck('id')->first();
        }

        if (empty($id) && !empty($row['bill_number'])) {
            $id = Document::bill()->number($row['bill_number'])->pluck('id')->first();
        }

        if (empty($id) && !empty($row['invoice_bill_number'])) {
            if ($row['type'] == 'income') {
                $id = Document::invoice()->number($row['invoice_bill_number'])->pluck('id')->first();
            } else {
                $id = Document::bill()->number($row['invoice_bill_number'])->pluck('id')->first();
            }
        }

        return is_null($id) ? $id : (int) $id;
    }

    public function getItemId($row)
    {
        $id = isset($row['item_id']) ? $row['item_id'] : null;

        if (empty($id) && !empty($row['item_name'])) {
            $id = $this->getItemIdFromName($row);
        }

        return is_null($id) ? $id : (int) $id;
    }

    public function getTaxId($row)
    {
        $id = isset($row['tax_id']) ? $row['tax_id'] : null;

        if (empty($id) && !empty($row['tax_name'])) {
            $id = Tax::name($row['tax_name'])->pluck('id')->first();
        }

        if (empty($id) && !empty($row['tax_rate'])) {
            $id = $this->getTaxIdFromRate($row);
        }

        return is_null($id) ? $id : (int) $id;
    }

    public function getAccountIdFromCurrency($row)
    {
        return Account::firstOrCreate([
            'company_id'        => company_id(),
            'currency_code'     => $row['currency_code'],
        ], [
            'name'              => !empty($row['account_name']) ? $row['account_name'] : $row['currency_code'],
            'number'            => !empty($row['account_number']) ? $row['account_number'] : rand(1, 10000),
            'opening_balance'   => !empty($row['opening_balance']) ? $row['opening_balance'] : 0,
            'enabled'           => 1,
        ])->id;
    }

    public function getAccountIdFromName($row)
    {
        return Account::firstOrCreate([
            'company_id'        => company_id(),
            'name'              => $row['account_name'],
        ], [
            'number'            => !empty($row['account_number']) ? $row['account_number'] : rand(1, 10000),
            'currency_code'     => !empty($row['currency_code']) ? $row['currency_code'] : setting('default.currency'),
            'opening_balance'   => !empty($row['opening_balance']) ? $row['opening_balance'] : 0,
            'enabled'           => 1,
        ])->id;
    }

    public function getAccountIdFromNumber($row)
    {
        return Account::firstOrCreate([
            'company_id'        => company_id(),
            'number'            => $row['account_number'],
        ], [
            'name'              => !empty($row['account_name']) ? $row['account_name'] : $row['account_number'],
            'currency_code'     => !empty($row['currency_code']) ? $row['currency_code'] : setting('default.currency'),
            'opening_balance'   => !empty($row['opening_balance']) ? $row['opening_balance'] : 0,
            'enabled'           => 1,
        ])->id;
    }

    public function getCategoryIdFromName($row, $type)
    {
        return Category::firstOrCreate([
            'company_id'        => company_id(),
            'name'              => $row['category_name'],
        ], [
            'type'              => $type,
            'color'             => !empty($row['category_color']) ? $row['category_color'] : '#' . dechex(rand(0x000000, 0xFFFFFF)),
            'enabled'           => 1,
        ])->id;
    }

    public function getContactIdFromEmail($row, $type)
    {
        return Contact::firstOrCreate([
            'company_id'        => company_id(),
            'email'             => $row['contact_email'],
        ], [
            'type'              => $type,
            'name'              => !empty($row['contact_name']) ? $row['contact_name'] : $row['contact_email'],
            'currency_code'     => !empty($row['contact_currency']) ? $row['contact_currency'] : setting('default.currency'),
            'enabled'           => 1,
        ])->id;
    }

    public function getContactIdFromName($row, $type)
    {
        return Contact::firstOrCreate([
            'company_id'        => company_id(),
            'name'              => $row['contact_name'],
        ], [
            'type'              => $type,
            'currency_code'     => !empty($row['contact_currency']) ? $row['contact_currency'] : setting('default.currency'),
            'enabled'           => 1,
        ])->id;
    }

    public function getItemIdFromName($row)
    {
        return Item::firstOrCreate([
            'company_id'        => company_id(),
            'name'              => $row['item_name'],
        ], [
            'sale_price'        => !empty($row['sale_price']) ? $row['sale_price'] : (!empty($row['price']) ? $row['price'] : 0),
            'purchase_price'    => !empty($row['purchase_price']) ? $row['purchase_price'] : (!empty($row['price']) ? $row['price'] : 0),
            'enabled'           => 1,
        ])->id;
    }

    public function getTaxIdFromRate($row, $type = 'normal')
    {
        return Tax::firstOrCreate([
            'company_id'        => company_id(),
            'rate'              => $row['tax_rate'],
        ], [
            'type'              => $type,
            'name'              => !empty($row['tax_name']) ? $row['tax_name'] : $row['tax_rate'],
            'enabled'           => 1,
        ])->id;
    }
}
