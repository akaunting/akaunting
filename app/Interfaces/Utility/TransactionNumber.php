<?php

namespace App\Interfaces\Utility;

use App\Models\Common\Contact;

interface TransactionNumber
{
    public function getNextNumber(string $type, string $suffix, ?Contact $contact): string;

    public function increaseNextNumber(string $type, string $suffix, ?Contact $contact): void;
}