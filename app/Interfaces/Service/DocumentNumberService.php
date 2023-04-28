<?php

namespace App\Interfaces\Service;

use App\Models\Common\Contact;

interface DocumentNumberService
{
    public function getNextDocumentNumber(string $type, ?Contact $contact): string;

    public function increaseNextDocumentNumber(string $type, ?Contact $contact): void;
}
