<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Events\Common\ContactDeleted;
use App\Events\Common\ContactDeleting;
use App\Interfaces\Job\ShouldDelete;
use App\Jobs\Auth\DeleteUser;
use App\Traits\Contacts;

class DeleteContact extends Job implements ShouldDelete
{
    use Contacts;

    public function handle() :bool
    {
        $this->authorize();

        event(new ContactDeleting($this->model));

        \DB::transaction(function () {
            $this->deleteRelationships($this->model, ['contact_persons']);

            if ($user = $this->model->user) {
                $this->dispatch(new DeleteUser($user));
            }

            $this->model->delete();
        });

        event(new ContactDeleted($this->model));

        return true;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        if ($relationships = $this->getRelationships()) {
            $message = trans('messages.warning.deleted', ['name' => $this->model->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships(): array
    {
        $rels = [
            'transactions' => 'transactions',
        ];

        if ($this->isCustomer()) {
            $rels['invoices'] = 'invoices';
        } else {
            $rels['bills'] = 'bills';
        }

        return $this->countRelationships($this->model, $rels);
    }
}
