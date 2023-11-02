<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Common\Contact;
use App\Models\Common\ContactPerson;

class CreateContactPersons extends Job implements HasOwner, HasSource, ShouldCreate
{
    protected $contact;

    protected $request;

    public function __construct(Contact $contact, $request)
    {
        $this->contact = $contact;
        $this->request = $request;

        parent::__construct($contact, $request);
    }

    /**
     * Execute the job.
     *
     * @return mixed
     * @todo type hint after upgrading to PHP 8
     */
    public function handle()
    {
        if (empty($this->request['contact_persons'])) {
            return false;
        }

        \DB::transaction(function () {
            foreach ($this->request['contact_persons'] as $person) {
                if (empty($person['name']) && empty($person['email']) && empty($person['phone'])) {
                    continue;
                }

                ContactPerson::create([
                    'company_id' => $this->contact->company_id,
                    'type' => $this->contact->type,
                    'contact_id' => $this->contact->id,
                    'name' => $person['name'] ?? null,
                    'email' => $person['email'] ?? null,
                    'phone' => $person['phone'] ?? null,
                    'created_from' => $this->request['created_from'],
                    'created_by' => $this->request['created_by'],
                ]);
            }
        });

        return $this->contact->contact_persons;
    }
}
