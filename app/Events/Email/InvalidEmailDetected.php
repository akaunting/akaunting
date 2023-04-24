<?php

namespace App\Events\Email;

use App\Abstracts\Event;
use App\Models\Auth\User;
use App\Models\Common\Contact;

class InvalidEmailDetected extends Event
{
    public $email;

    public $error;

    public $contact = null;

    public $user = null;

    public function __construct(string $email, string $error)
    {
        $this->email = $email;

        $this->error = $error;

        $this->setContact();

        $this->setUser();
    }

    public function setContact()
    {
        $contact = Contact::email($this->email)->enabled()->first();

        if (empty($contact)) {
            return;
        }

        $this->contact = $contact;
    }

    public function setUser()
    {
        $user = User::email($this->email)->enabled()->first();

        if (empty($user)) {
            return;
        }

        $this->user = $user;
    }
}
