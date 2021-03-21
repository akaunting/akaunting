<?php

namespace App\Listeners\Document;

use App\Events\Document\PaymentReceived as Event;
use App\Jobs\Banking\CreateBankingDocumentTransaction;
use App\Traits\Jobs;
use Illuminate\Support\Str;

class CreateDocumentTransaction
{
    use Jobs;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return array
     */
    public function handle(Event $event)
    {
        $document = $event->document;
        $request = $event->request;

        try {
            $this->dispatch(new CreateBankingDocumentTransaction($document, $request));
        } catch (\Exception $e) {
            $message = $e->getMessage();

            $user = user();

            $type = Str::plural($event->document->type);

            if (empty($user)) {
                flash($message)->error()->important();

                redirect()->route("signed.$type.show", $document->id)->send();
            }

            if ($user->can('read-client-portal')) {
                flash($message)->error()->important();

                redirect()->route("portal.$type.show", $document->id)->send();
            }

            throw new \Exception($message);
        }
    }
}
