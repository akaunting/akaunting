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

            $signed = request()->isSigned($document->company_id);

            if (empty($user) || $signed) {
                flash($message)->error()->important();

                return $this->getResponse('signed.' . $type . '.show', $document, $message);
            }

            if ($user->can('read-client-portal')) {
                flash($message)->error()->important();

                return $this->getResponse('portal.' . $type . '.show', $document, $message);
            }

            throw new \Exception($message);
        }
    }

    protected function getResponse($path, $document, $message)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'errors' => true,
                'message' => $message,
                'redirect' => route($path, $document->id)
            ]);
        }

        return redirect()->route($path, $document->id);
    }
}
