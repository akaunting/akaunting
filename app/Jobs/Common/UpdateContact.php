<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Common\Contact;
use Illuminate\Support\Str;

class UpdateContact extends Job
{
    protected $contact;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $contact
     * @param  $request
     */
    public function __construct($contact, $request)
    {
        $this->contact = $contact;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Contact
     */
    public function handle()
    {
        $this->authorize();

        \DB::transaction(function () {
            if ($this->request->get('create_user', 'false') === 'true') {
                $this->createUser();
            } elseif ($this->contact->user) {
                $this->contact->user->update($this->request->all());
            }

            // Upload logo
            if ($this->request->file('logo')) {
                $media = $this->getMedia($this->request->file('logo'), Str::plural($this->contact->type));

                $this->contact->attachMedia($media, 'logo');
            }

            $this->contact->update($this->request->all());
        });

        return $this->contact;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if (($this->request['enabled'] == 0) && ($relationships = $this->getRelationships())) {
            $message = trans('messages.warning.disabled', ['name' => $this->contact->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function createUser()
    {
        // Check if user exist
        if ($user = User::where('email', $this->request['email'])->first()) {
            $message = trans('messages.error.customer', ['name' => $user->name]);

            throw new \Exception($message);
        }

        $data = $this->request->all();
        $data['locale'] = setting('default.locale', 'en-GB');

        $customer_role = Role::all()->filter(function ($role) {
            return $role->hasPermission('read-client-portal');
        })->first();

        $user = User::create($data);
        $user->roles()->attach($customer_role);
        $user->companies()->attach($data['company_id']);

        $this->request['user_id'] = $user->id;
    }

    public function getRelationships()
    {
        $rels = [
            'transactions' => 'transactions',
        ];

        if ($this->contact->type == 'customer') {
            $rels['invoices'] = 'invoices';
        } else {
            $rels['bills'] = 'bills';
        }

        return $this->countRelationships($this->contact, $rels);
    }
}
