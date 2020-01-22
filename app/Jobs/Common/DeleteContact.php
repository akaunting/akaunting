<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Traits\Contacts;

class DeleteContact extends Job
{
    use Contacts;

    protected $contact;

    /**
     * Create a new job instance.
     *
     * @param  $contact
     */
    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        $this->authorize();

        $this->contact->delete();

        return true;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if ($relationships = $this->getRelationships()) {
            $message = trans('messages.warning.deleted', ['name' => $this->contact->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships()
    {
        $rels = [
            'transactions' => 'transactions',
        ];

        if (in_array($this->contact->type, $this->getCustomerTypes())) {
            $rels['invoices'] = 'invoices';
        } else {
            $rels['bills'] = 'bills';
        }

        return $this->countRelationships($this->contact, $rels);
    }
}
