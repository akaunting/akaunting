<?php

namespace App\Interfaces\Utility;

use App\Models\Common\Contact;

interface DocumentNumber
{
    public function getNextNumber(string $type, ?Contact $contact): string;

    public function increaseNextNumber(string $type, ?Contact $contact): void;
}
