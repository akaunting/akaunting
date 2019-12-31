<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Contact as Request;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\DeleteContact;
use App\Jobs\Common\UpdateContact;
use App\Models\Common\Contact;
use App\Traits\Uploads;
use App\Transformers\Common\Contact as Transformer;

class Contacts extends ApiController
{
    use Uploads;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-sales-customers')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-sales-customers')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-sales-customers')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-sales-customers')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $contacts = Contact::collect();

        return $this->response->paginator($contacts, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        // Check if we're querying by id or email
        if (is_numeric($id)) {
            $contact = Contact::find($id);
        } else {
            $contact = Contact::where('email', $id)->first();
        }

        return $this->response->item($contact, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $contact = $this->dispatch(new CreateContact($request));

        return $this->response->created(url('api/contacts/' . $contact->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $contact
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Contact $contact, Request $request)
    {
        $contact = $this->dispatch(new UpdateContact($contact, $request));

        return $this->item($contact->fresh(), new Transformer());
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Contact $contact
     * @return \Dingo\Api\Http\Response
     */
    public function enable(Contact $contact)
    {
        $contact = $this->dispatch(new UpdateContact($contact, request()->merge(['enabled' => 1])));

        return $this->item($contact->fresh(), new Transformer());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Contact $contact
     * @return \Dingo\Api\Http\Response
     */
    public function disable(Contact $contact)
    {
        try {
            $contact = $this->dispatch(new UpdateContact($contact, request()->merge(['enabled' => 0])));

            return $this->item($contact->fresh(), new Transformer());
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Contact $contact
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Contact $contact)
    {
        try {
            $this->dispatch(new DeleteContact($contact));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
