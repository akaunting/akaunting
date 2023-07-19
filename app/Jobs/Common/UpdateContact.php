<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use App\Jobs\Auth\CreateUser;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Common\Contact;
use Illuminate\Support\Str;

class UpdateContact extends Job implements ShouldUpdate
{
    public function handle(): Contact
    {
        $this->authorize();

        \DB::transaction(function () {
            if ($this->request->get('create_user', 'false') === 'true') {
                $this->createUser();
            } elseif ($this->model->user) {
                $this->model->user->update($this->request->all());
            }

            // Upload logo
            if ($this->request->file('logo')) {
                $media = $this->getMedia($this->request->file('logo'), Str::plural($this->model->type));

                $this->model->attachMedia($media, 'logo');
            } elseif (! $this->request->file('logo') && $this->model->logo) {
                $this->deleteMediaModel($this->model, 'logo', $this->request);
            }

            $this->updateRecurringDocument();

            $this->model->update($this->request->all());
        });

        return $this->model;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        if (($this->request['enabled'] == 0) && ($relationships = $this->getRelationships())) {
            $message = trans('messages.warning.disabled', ['name' => $this->model->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function createUser(): void
    {
        // Check if user exist
        if ($user = User::where('email', $this->request['email'])->first()) {
            $message = trans('messages.error.customer', ['name' => $user->name]);

            throw new \Exception($message);
        }

        $customer_role_id = Role::all()->filter(function ($role) {
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
        $recurring = $this->model->document_recurring;

        if ($recurring) {
            foreach ($recurring as $recur) {
                $recur->update([
                    'contact_name' => $this->request['name'],
                    'contact_email' => $this->request['email'],
                    'contact_tax_number' => $this->request['tax_number'],
                    'contact_phone' => $this->request['phone'],
                    'contact_address' => $this->request['address'],
                    'contact_city' => $this->request['city'],
                    'contact_state' => $this->request['state'],
                    'contact_zip_code' => $this->request['zip_code'],
                    'contact_country' => $this->request['country'],
                ]);
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
}
