<?php

namespace App\Abstracts;

use App\Jobs\Common\DeleteContact;
use App\Jobs\Common\UpdateContact;
use App\Jobs\Banking\DeleteTransaction;
use App\Traits\Jobs;
use App\Traits\Relationships;
use App\Utilities\Export;
use App\Utilities\Import;
use Illuminate\Support\Arr;

abstract class BulkAction
{
    use Jobs, Relationships;

    public $model = false;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-common-items',
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-common-items',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-common-items',
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.export',
            'type' => 'download'
        ],
    ];

    public function getSelectedRecords($request, $relationships = null)
    {
        if (empty($relationships)) {
            $model = $this->model::query();
        } else {
            $relationships = Arr::wrap($relationships);

            $model = $this->model::with($relationships);
        }

        return $model->find($this->getSelectedInput($request));
    }

    public function getSelectedInput($request)
    {
        return $request->get('selected', []);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  $request
     *
     * @return Response
     */
    public function duplicate($request)
    {
        $items = $this->getSelectedRecords($request);

        foreach ($items as $item) {
            $item->duplicate();
        }
    }

    /**
     * Enable the specified resource.
     *
     * @param  $request
     *
     * @return Response
     */
    public function enable($request)
    {
        $items = $this->getSelectedRecords($request);

        foreach ($items as $item) {
            $item->enabled = true;
            $item->save();
        }
    }

    /**
     * Disable the specified resource.
     *
     * @param  $request
     *
     * @return Response
     */
    public function disable($request)
    {
        $items = $this->getSelectedRecords($request);

        foreach ($items as $item) {
            $item->enabled = false;
            $item->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $request
     *
     * @return Response
     */
    public function delete($request)
    {
        $this->destroy($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $request
     *
     * @return Response
     */
    public function destroy($request)
    {
        $items = $this->getSelectedRecords($request);

        foreach ($items as $item) {
            $item->delete();
        }
    }

    public function disableContacts($request)
    {
        $contacts = $this->getSelectedRecords($request, 'user');

        foreach ($contacts as $contact) {
            try {
                $this->dispatch(new UpdateContact($contact, request()->merge(['enabled' => 0])));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function deleteContacts($request)
    {
        $contacts = $this->getSelectedRecords($request, 'user');

        foreach ($contacts as $contact) {
            try {
                $this->dispatch(new DeleteContact($contact));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function deleteTransactions($request)
    {
        $transactions = $this->getSelectedRecords($request, 'category');

        foreach ($transactions as $transaction) {
            try {
                $this->dispatch(new DeleteTransaction($transaction));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    /**
     * Import the excel file or catch errors
     *
     * @param $class
     * @param $request
     * @param $translation
     *
     * @return array
     */
    public function importExcel($class, $request, $translation)
    {
        return Import::fromExcel($class, $request, $translation);
    }

    /**
     * Export the excel file or catch errors
     *
     * @param $class
     * @param $translation
     * @param $extension
     *
     * @return mixed
     */
    public function exportExcel($class, $translation, $extension = 'xlsx')
    {
        return Export::toExcel($class, $translation, $extension);
    }
}
