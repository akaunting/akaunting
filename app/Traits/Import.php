<?php

namespace App\Traits;

use App\Events\Common\ImportViewCreating;
use App\Events\Common\ImportViewCreated;
use App\Http\Requests\Banking\Account as AccountRequest;
use App\Http\Requests\Common\Contact as ContactRequest;
use App\Http\Requests\Common\Item as ItemRequest;
use App\Http\Requests\Setting\Category as CategoryRequest;
use App\Http\Requests\Setting\Currency as CurrencyRequest;
use App\Http\Requests\Setting\Tax as TaxRequest;
use App\Jobs\Banking\CreateAccount;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\CreateItem;
use App\Jobs\Setting\CreateCategory;
use App\Jobs\Setting\CreateCurrency;
use App\Jobs\Setting\CreateTax;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Traits\Jobs;
use App\Traits\Sources;
use App\Traits\Translations;
use App\Utilities\Modules;
use Illuminate\Support\Facades\Validator;
use Akaunting\Module\Module;

trait Import
{
    use Jobs, Sources, Translations;

    public function getImportView($group, $type, $route = null)
    {
        // Get the view path
        $view = $this->getImportViewPath($group, $type);

        // Get import blade variables
        $path = $this->getImportPath($group, $type);
        $title_type = $this->getImportTitleType($group, $type);
        $sample_file = $this->getImportSampleFile($group, $type);
        $form_params = $this->getImportFormParams($group, $type, $path, $route);
        $document_link = $this->getImportDocumentLink($group, $type);

        // Create the import view
        $import = new \stdClass();
        $import->view = $view;
        $import->data = compact('group', 'type', 'route', 'path', 'title_type', 'sample_file', 'form_params', 'document_link');

        event(new ImportViewCreating($import));

        event(new ImportViewCreated($import));

        return [
            $import->view, 
            $import->data
        ];
    }

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

    public function getCategoryType($type)
    {
        return array_key_exists($type, config('type.category')) ? $type : 'other';
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

    public function getCurrencyCode($row)
    {
        $currency = Currency::where('code', $row['currency_code'])->first();

        if (!empty($currency)) {
            return $currency->code;
        }

        try {
            $data = [
                'company_id'            => company_id(),
                'code'                  => $row['currency_code'],
                'name'                  => isset($row['currency_name']) ? $row['currency_name'] : currency($row['currency_code'])->getName(),
                'rate'                  => isset($row['currency_rate']) ? $row['currency_rate'] : 1,
                'symbol'                => isset($row['currency_symbol']) ? $row['currency_symbol'] : currency($row['currency_code'])->getSymbol(),
                'precision'             => isset($row['currency_precision']) ? $row['currency_precision'] : currency($row['currency_code'])->getPrecision(),
                'decimal_mark'          => isset($row['currency_decimal_mark']) ? $row['currency_decimal_mark'] : currency($row['currency_code'])->getDecimalMark(),
                'thousands_separator'   => isset($row['currency_thousands_separator']) ? $row['currency_thousands_separator'] : currency($row['currency_code'])->getThousandsSeparator(),
                'created_from'          => !empty($row['created_from']) ? $row['created_from'] : $this->getSourcePrefix() . 'import',
                'created_by'            => !empty($row['created_by']) ? $row['created_by'] : user()?->id,
            ];
        } catch (\OutOfBoundsException $e) {
            return default_currency();
        }

        Validator::validate($data, (new CurrencyRequest)->rules());

        $currency = $this->dispatch(new CreateCurrency($data));

        return $currency->code;
    }

    public function getCreatedById($row)
    {
        if (empty($row['created_by'])) {
            return $this->user?->id;
        }

        $user = user_model_class()::where('email', $row['created_by'])->first();

        if (! empty($user)) {
            return $user->id;
        }

        return $this->user->id;
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

    public function getParentId($row)
    {
        $id = isset($row['parent_id']) ? $row['parent_id'] : null;

        if (empty($row['parent_number']) && empty($row['parent_name'])){
            return null;
        }

        if (empty($id) && (!empty($row['document_number']) || !empty($row['invoice_number']) || !empty($row['bill_number']))) {
            $id = Document::number($row['parent_number'])->pluck('id')->first();
        }

        if (empty($id) && isset($row['number'])) {
            $id = Transaction::number($row['parent_number'])->pluck('id')->first();
        }

        if (empty($id) && isset($row['parent_name'])) {
            $id = Category::type($row['type'])->withSubCategory()->where('name', $row['parent_name'])->pluck('id')->first();
        }

        return is_null($id) ? $id : (int) $id;
    }

    public function getPaymentMethod($row)
    {
        Modules::clearPaymentMethodsCache();

        $methods = Modules::getPaymentMethods('all');

        $payment_method = isset($row['payment_method']) ? $row['payment_method'] : null;

        if (array_key_exists($payment_method, $methods)) {
            return $payment_method;
        }

        if (module_is_enabled('offline-payments')) {
            $offline_payment = $this->dispatch(new \Modules\OfflinePayments\Jobs\CreatePaymentMethod([
                'name'          => $payment_method,
                'customer'      => 1,
                'order'         => count($methods) + 1,
                'description'   => '',
            ]));

            $payment_method = $offline_payment['code'];
        }

        return $payment_method;
    }

    public function getItemId($row, $type = null)
    {
        $id = isset($row['item_id']) ? $row['item_id'] : null;

        $type = !empty($type) ? $type : (!empty($row['item_type']) ? $row['item_type'] : 'product');

        if (empty($id) && !empty($row['item_name'])) {
            $id = $this->getItemIdFromName($row, $type);
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
        $account_id = Account::where('currency_code', $row['currency_code'])->pluck('id')->first();

        if (!empty($account_id)) {
            return $account_id;
        }

        $data = [
            'company_id'        => company_id(),
            'type'              => !empty($row['account_type']) ? $row['account_type'] : 'bank',
            'currency_code'     => $row['currency_code'],
            'name'              => !empty($row['account_name']) ? $row['account_name'] : $row['currency_code'],
            'number'            => !empty($row['account_number']) ? $row['account_number'] : (string) rand(1, 10000),
            'opening_balance'   => !empty($row['opening_balance']) ? $row['opening_balance'] : 0,
            'enabled'           => 1,
            'created_from'      => !empty($row['created_from']) ? $row['created_from'] : $this->getSourcePrefix() . 'import',
            'created_by'        => !empty($row['created_by']) ? $row['created_by'] : user()?->id,
        ];

        Validator::validate($data, (new AccountRequest)->rules());

        $account = $this->dispatch(new CreateAccount($data));

        return $account->id;
    }

    public function getAccountIdFromName($row)
    {
        $account_id = Account::where('name', $row['account_name'])->pluck('id')->first();

        if (!empty($account_id)) {
            return $account_id;
        }

        $data = [
            'company_id'        => company_id(),
            'type'              => !empty($row['account_type']) ? $row['account_type'] : 'bank',
            'name'              => $row['account_name'],
            'number'            => !empty($row['account_number']) ? $row['account_number'] : (string) rand(1, 10000),
            'currency_code'     => !empty($row['currency_code']) ? $row['currency_code'] : default_currency(),
            'opening_balance'   => !empty($row['opening_balance']) ? $row['opening_balance'] : 0,
            'enabled'           => 1,
            'created_from'      => !empty($row['created_from']) ? $row['created_from'] : $this->getSourcePrefix() . 'import',
            'created_by'        => !empty($row['created_by']) ? $row['created_by'] : user()?->id,
        ];

        Validator::validate($data, (new AccountRequest)->rules());

        $account = $this->dispatch(new CreateAccount($data));

        return $account->id;
    }

    public function getAccountIdFromNumber($row)
    {
        $account_id = Account::where('account_number', $row['account_number'])->pluck('id')->first();

        if (!empty($account_id)) {
            return $account_id;
        }

        $data = [
            'company_id'        => company_id(),
            'type'              => !empty($row['account_type']) ? $row['account_type'] : 'bank',
            'number'            => $row['account_number'],
            'name'              => !empty($row['account_name']) ? $row['account_name'] : $row['account_number'],
            'currency_code'     => !empty($row['currency_code']) ? $row['currency_code'] : default_currency(),
            'opening_balance'   => !empty($row['opening_balance']) ? $row['opening_balance'] : 0,
            'enabled'           => 1,
            'created_from'      => !empty($row['created_from']) ? $row['created_from'] : $this->getSourcePrefix() . 'import',
            'created_by'        => !empty($row['created_by']) ? $row['created_by'] : user()?->id,
        ];

        Validator::validate($data, (new AccountRequest)->rules());

        $account = $this->dispatch(new CreateAccount($data));

        return $account->id;
    }

    public function getCategoryIdFromName($row, $type)
    {
        $category_id = Category::type($type)->withSubCategory()->where('name', $row['category_name'])->pluck('id')->first();

        if (!empty($category_id)) {
            return $category_id;
        }

        $data = [
            'company_id'        => company_id(),
            'name'              => $row['category_name'],
            'type'              => $type,
            'color'             => !empty($row['category_color']) ? $row['category_color'] : '#' . dechex(rand(0x000000, 0xFFFFFF)),
            'enabled'           => 1,
            'created_from'      => !empty($row['created_from']) ? $row['created_from'] : $this->getSourcePrefix() . 'import',
            'created_by'        => !empty($row['created_by']) ? $row['created_by'] : user()?->id,
        ];

        Validator::validate($data, (new CategoryRequest)->rules());

        $category = $this->dispatch(new CreateCategory($data));

        return $category->id;
    }

    public function getContactIdFromEmail($row, $type)
    {
        $contact_id = Contact::type($type)->where('email', $row['contact_email'])->pluck('id')->first();

        if (!empty($contact_id)) {
            return $contact_id;
        }

        $data = [
            'company_id'        => company_id(),
            'email'             => $row['contact_email'],
            'type'              => $type,
            'name'              => !empty($row['contact_name']) ? $row['contact_name'] : $row['contact_email'],
            'currency_code'     => !empty($row['contact_currency']) ? $row['contact_currency'] : default_currency(),
            'enabled'           => 1,
            'created_from'      => !empty($row['created_from']) ? $row['created_from'] : $this->getSourcePrefix() . 'import',
            'created_by'        => !empty($row['created_by']) ? $row['created_by'] : user()?->id,
        ];

        Validator::validate($data, (new ContactRequest)->rules());

        $contact = $this->dispatch(new CreateContact($data));

        return $contact->id;
    }

    public function getContactIdFromName($row, $type)
    {
        $contact_id = Contact::type($type)->where('name', $row['contact_name'])->pluck('id')->first();

        if (!empty($contact_id)) {
            return $contact_id;
        }

        $data = [
            'company_id'        => company_id(),
            'name'              => $row['contact_name'],
            'type'              => $type,
            'email'             => !empty($row['contact_email']) ? $row['contact_email'] : null,
            'currency_code'     => !empty($row['contact_currency']) ? $row['contact_currency'] : default_currency(),
            'enabled'           => 1,
            'created_from'      => !empty($row['created_from']) ? $row['created_from'] : $this->getSourcePrefix() . 'import',
            'created_by'        => !empty($row['created_by']) ? $row['created_by'] : user()?->id,
        ];

        Validator::validate($data, (new ContactRequest)->rules());

        $contact = $this->dispatch(new CreateContact($data));

        return $contact->id;
    }

    public function getItemIdFromName($row, $type = null)
    {
        $type = !empty($type) ? $type : (!empty($row['item_type']) ? $row['item_type'] : 'product');

        $item_id = Item::type($type)->where('name', $row['item_name'])->pluck('id')->first();

        if (!empty($item_id)) {
            return $item_id;
        }

        $data = [
            'company_id'        => company_id(),
            'type'              => $type,
            'name'              => $row['item_name'],
            'description'       => !empty($row['item_description']) ? $row['item_description'] : null,
            'sale_price'        => !empty($row['sale_price']) ? $row['sale_price'] : (!empty($row['price']) ? $row['price'] : 0),
            'purchase_price'    => !empty($row['purchase_price']) ? $row['purchase_price'] : (!empty($row['price']) ? $row['price'] : 0),
            'enabled'           => 1,
            'created_from'      => !empty($row['created_from']) ? $row['created_from'] : $this->getSourcePrefix() . 'import',
            'created_by'        => !empty($row['created_by']) ? $row['created_by'] : user()?->id,
        ];

        Validator::validate($data, (new ItemRequest())->rules());

        $item = $this->dispatch(new CreateItem($data));

        return $item->id;
    }

    public function getTaxIdFromRate($row, $type = 'normal')
    {
        $tax_id = Tax::type($type)->where('rate', $row['tax_rate'])->pluck('id')->first();

        if (!empty($tax_id)) {
            return $tax_id;
        }

        $data = [
            'company_id'        => company_id(),
            'rate'              => $row['tax_rate'],
            'type'              => $type,
            'name'              => !empty($row['tax_name']) ? $row['tax_name'] : (string) $row['tax_rate'],
            'enabled'           => 1,
            'created_from'      => !empty($row['created_from']) ? $row['created_from'] : $this->getSourcePrefix() . 'import',
            'created_by'        => !empty($row['created_by']) ? $row['created_by'] : user()?->id,
        ];

        Validator::validate($data, (new TaxRequest())->rules());

        $tax = $this->dispatch(new CreateTax($data));

        return $tax->id;
    }

    protected function getImportPath($group, $type)
    {
        $path = config('import.' . $group . '.' . $type . '.path');

        if (! empty($path)) {
            return str_replace('company_id', company_id(), $path);
        }

        return company_id() . '/' . $group . '/' . $type;
    }

    protected function getImportTitleType($group, $type)
    {
        $title_type = config('import.' . $group . '.' . $type . '.title_type');

        if (! empty($title_type)) {
            return $this->findTranslation($title_type);
        }

        $module = module($group);

        $title_type = trans_choice('general.' . str_replace('-', '_', $type), 2);

        if ($module instanceof Module) {
            $title_type = trans_choice($group . '::general.' . str_replace('-', '_', $type), 2);
        }

        return $title_type;
    }

    protected function getImportSampleFile($group, $type)
    {
        $sample_file = config('import.' . $group . '.' . $type . '.sample_file');

        if (! empty($sample_file)) {
            return url($sample_file);
        }

        $module = module($group);

        $sample_file = url('public/files/import/' . $type . '.xlsx');

        if ($module instanceof Module) {
            $sample_file = url('modules/' . $module->getStudlyName() . '/Resources/assets/' . $type . '.xlsx');
        }

        return $sample_file;
    }

    protected function getImportFormParams($group, $type, $path = null, $route = null)
    {
        $form_params = config('import.' . $group . '.' . $type . '.form_params');

        if (! empty($form_params)) {
            return $form_params;
        }

        $form_params = [
            'id' => 'import',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true,
            'route' => '',
            'url' => '',
        ];

        if (! empty($route)) {
            $form_params['route'] = $route;
        } else {
            $form_params['url'] = $path . '/import';
        }

        return $form_params;
    }

    protected function getImportDocumentLink($group, $type)
    {
        $document_link = config('import.' . $group . '.' . $type . '.document_link');

        if (! empty($document_link)) {
            return $document_link;
        }

        $document_link = 'https://akaunting.com/hc/docs/import-export/';

        return $document_link;
    }

    protected function getImportViewPath($group, $type)
    {
        $view = config('import.' . $group . '.' . $type . '.view');

        if (! empty($view)) {
            return $view;
        }

        $view = 'common.import.create';

        return $view;
    }
}
