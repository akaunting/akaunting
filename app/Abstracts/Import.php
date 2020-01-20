<?php

namespace App\Abstracts;

use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Setting\Category;
use App\Models\Setting\Tax;
use Illuminate\Support\Str;
use Jenssegers\Date\Date;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

abstract class Import implements ToModel, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading, WithHeadingRow, WithMapping, WithValidation
{
    use Importable;

    public $empty_field = 'empty---';

    public function map($row): array
    {
        $row['company_id'] = session('company_id');

        // Make enabled field integer
        if (isset($row['enabled'])) {
            $row['enabled'] = (int) $row['enabled'];
        }

        // Make reconciled field integer
        if (isset($row['reconciled'])) {
            $row['reconciled'] = (int) $row['reconciled'];
        }

        $date_fields = ['paid_at', 'invoiced_at', 'billed_at', 'due_at', 'issued_at', 'created_at'];
        foreach ($date_fields as $date_field) {
            if (!isset($row[$date_field])) {
                continue;
            }

            $row[$date_field] = Date::parse($row[$date_field])->format('Y-m-d H:i:s');
        }

        return $row;
    }

    public function rules(): array
    {
        return [];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function onFailure(Failure ...$failures)
    {
        $sheet = Str::snake((new \ReflectionClass($this))->getShortName());

        foreach ($failures as $failure) {
            // @todo remove after 3.2 release https://github.com/Maatwebsite/Laravel-Excel/issues/1834#issuecomment-474340743
            if (collect($failure->values())->first() == $this->empty_field) {
                continue;
            }

            $message = trans('messages.error.import_column', [
                'message' => collect($failure->errors())->first(),
                'sheet' => $sheet,
                'line' => $failure->row(),
            ]);

            flash($message)->error()->important();
       }
    }

    public function onError(\Throwable $e)
    {
        flash($e->getMessage())->error()->important();
    }

    public function getAccountIdFromCurrency($row)
    {
        return Account::firstOrCreate([
            'currency_code'     => $row['currency_code'],
        ], [
            'company_id'        => session('company_id'),
            'name'              => $row['currency_code'],
            'number'            => Account::max('number') + 1,
            'opening_balance'   => 0,
            'enabled'           => 1,
        ])->id;
    }

    public function getAccountIdFromName($row)
    {
        return Account::firstOrCreate([
            'name'              => $row['account_name'],
        ], [
            'company_id'        => session('company_id'),
            'number'            => Account::max('number') + 1,
            'currency_code'     => setting('default.currency'),
            'opening_balance'   => 0,
            'enabled'           => 1,
        ])->id;
    }

    public function getAccountIdFromNumber($row)
    {
        return Account::firstOrCreate([
            'number'            => $row['account_number'],
        ], [
            'company_id'        => session('company_id'),
            'name'              => $row['account_number'],
            'currency_code'     => setting('default.currency'),
            'opening_balance'   => 0,
            'enabled'           => 1,
        ])->id;
    }

    public function getCategoryIdFromName($row, $type)
    {
        return Category::firstOrCreate([
            'name'              => $row['category_name'],
        ], [
            'company_id'        => session('company_id'),
            'type'              => $type,
            'color'             => '#' . dechex(rand(0x000000, 0xFFFFFF)),
            'enabled'           => 1,
        ])->id;
    }

    public function getContactIdFromEmail($row, $type)
    {
        return Contact::firstOrCreate([
            'email'             => $row['contact_email'],
        ], [
            'company_id'        => session('company_id'),
            'type'              => $type,
            'name'              => $row['contact_email'],
            'currency_code'     => setting('default.currency'),
            'enabled'           => 1,
        ])->id;
    }

    public function getContactIdFromName($row, $type)
    {
        return Contact::firstOrCreate([
            'name'              => $row['contact_name'],
        ], [
            'company_id'        => session('company_id'),
            'type'              => $type,
            'currency_code'     => setting('default.currency'),
            'enabled'           => 1,
        ])->id;
    }

    public function getItemIdFromName($row)
    {
        return Item::firstOrCreate([
            'name'              => $row['item_name'],
        ], [
            'company_id'        => session('company_id'),
            'sale_price'        => $row['price'],
            'purchase_price'    => $row['price'],
            'enabled'           => 1,
        ])->id;
    }

    public function getTaxIdFromRate($row, $type = 'normal')
    {
        return Tax::firstOrCreate([
            'rate'              => $row['tax_rate'],
        ], [
            'company_id'        => session('company_id'),
            'type'              => $type,
            'name'              => $row['tax_rate'],
            'enabled'           => 1,
        ])->id;
    }
}
