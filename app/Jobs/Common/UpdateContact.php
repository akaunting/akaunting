<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Events\Common\ContactUpdated;
use App\Events\Common\ContactUpdating;
use App\Exceptions\Job\NotAllowed;
use App\Interfaces\Job\ShouldUpdate;
use App\Jobs\Auth\CreateUser;
use App\Jobs\Common\CreateContactPersons;
use App\Models\Common\Contact;
use Illuminate\Support\Str;

class UpdateContact extends Job implements ShouldUpdate
{
    public function handle(): Contact
    {
        $this->authorize();

        event(new ContactUpdating($this->model, $this->request));

        \DB::transaction(function () {
            if ($this->request->get('create_user', 'false') === 'true') {
                $this->createUser();
            } elseif ($this->model->user) {
                $this->model->user->update($this->request->all());
            }

            // Upload logo
            if ($this->request->file('logo')) {
                $this->deleteMediaModel($this->model, 'logo', $this->request);

                $media = $this->getMedia($this->request->file('logo'), Str::plural($this->model->type));

                $this->model->attachMedia($media, 'logo');
            } elseif ($this->isWholeRecord() && $this->request->isNotApi() && ! $this->request->file('logo') && $this->model->logo) {
                $this->deleteMediaModel($this->model, 'logo', $this->request);
            } elseif ($this->request->isApi() && $this->request->has('remove_logo') && $this->model->logo) {
                $this->deleteMediaModel($this->model, 'logo', $this->request);
            }

            $this->updateRecurringDocument();

            if ($this->isWholeRecord() || $this->request->has('contact_persons')) {
                $this->deleteRelationships($this->model, ['contact_persons']);

                $this->dispatch(new CreateContactPersons($this->model, $this->request));
            }

            $this->model->update($this->request->all());
        });

        event(new ContactUpdated($this->model, $this->request));

        return $this->model;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        if (($this->request->has('enabled') && ! $this->request->get('enabled')) && ($relationships = $this->getRelationships())) {
            $message = trans('messages.warning.disabled', ['name' => $this->model->name, 'text' => implode(', ', $relationships)]);

            throw new NotAllowed($message);
        }
    }

    public function createUser(): void
    {
        // Check if user exist
        if ($user = user_model_class()::where('email', $this->request['email'])->first()) {
            $message = trans('messages.error.customer', ['name' => $user->name]);

            throw new \Exception($message);
        }

        $customer_role_id = role_model_class()::all()->filter(function ($role) {
            return $role->hasPermission('read-client-portal');
        })->pluck('id')->first();

        $this->request->merge([
            'locale' => setting('default.locale', 'en-GB'),
            'roles' => $customer_role_id,
            'companies' => [$this->request->get('company_id')],
        ]);

        $user = $this->dispatch(new CreateUser($this->request));

        $this->request['user_id'] = $user->id;
    }

    public function updateRecurringDocument(): void
    {
        $fields = [
            'name' => 'contact_name',
            'email' => 'contact_email',
            'tax_number' => 'contact_tax_number',
            'phone' => 'contact_phone',
            'address' => 'contact_address',
            'city' => 'contact_city',
            'state' => 'contact_state',
            'zip_code' => 'contact_zip_code',
            'country' => 'contact_country',
        ];

        // Only what is sent, as enabling, disabling and the bulk edit send few of these, if any
        $data = [];

        foreach ($fields as $field => $column) {
            if ($this->request->has($field)) {
                $data[$column] = $this->request->get($field);
            }
        }

        if (empty($data)) {
            return;
        }

        $recurring = $this->model->document_recurring;

        if ($recurring) {
            foreach ($recurring as $recur) {
                $recur->update($data);
            }
        }
    }

    public function getRelationships(): array
    {
        $rels = [
            'transactions' => 'transactions',
        ];

        if ($this->model->type == 'customer') {
            $rels['invoices'] = 'invoices';
        } else {
            $rels['bills'] = 'bills';
        }

        return $this->countRelationships($this->model, $rels);
    }

    /**
     * The forms and the API send the whole contact, its name included. Enabling, disabling and the
     * bulk edit send only what they change, so the contact persons are kept unless they are sent,
     * and the logo unless another one is.
     */
    protected function isWholeRecord(): bool
    {
        return $this->request->has('name');
    }
}
