<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Contact as Request;
use App\Http\Resources\Common\Contact as Resource;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\DeleteContact;
use App\Jobs\Common\UpdateContact;
use App\Models\Common\Contact;
use App\Traits\Uploads;

class Contacts extends ApiController
{
    use Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $contacts = Contact::collect();

        return Resource::collection($contacts);
    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Check if we're querying by id or email
        if (is_numeric($id)) {
            $contact = Contact::find($id);
        } else {
            $contact = Contact::where('email', $id)->first();
        }

        if (! $contact instanceof Contact) {
            //return $this->noContent();
            return $this->errorInternal('No query results for model [' . Contact::class . '] ' . $id);
        }

        return new Resource($contact);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $contact = $this->dispatch(new CreateContact($request));

        return $this->created(route('api.contacts.show', $contact->id), new Resource($contact));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $contact
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Contact $contact, Request $request)
    {
        $contact = $this->dispatch(new UpdateContact($contact, $request));

        return new Resource($contact->fresh());
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Contact $contact
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable(Contact $contact)
    {
        $contact = $this->dispatch(new UpdateContact($contact, request()->merge(['enabled' => 1])));

        return new Resource($contact->fresh());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Contact $contact
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable(Contact $contact)
    {
        try {
            $contact = $this->dispatch(new UpdateContact($contact, request()->merge(['enabled' => 0])));

            return new Resource($contact->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        try {
            $this->dispatch(new DeleteContact($contact));

            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
