<?php

namespace App\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\Company;
use App\Models\Setting\EmailTemplate;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class Version304 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '3.0.4';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        Log::channel('stdout')->info('Starting the Akaunting 3.0.4 update...');

        $this->updateDatabase();

        $this->updateCompanies();

        $this->deleteOldFiles();

        Log::channel('stdout')->info('Akaunting 3.0.4 update finished.');
    }

    public function updateDatabase()
    {
        Log::channel('stdout')->info('Updating database...');

        Artisan::call('migrate', ['--force' => true]);

        Log::channel('stdout')->info('Database updated.');
    }

    public function updateCompanies()
    {
        Log::channel('stdout')->info('Updating companies...');

        $company_id = company_id();

        $companies = Company::cursor();

        foreach ($companies as $company) {
            Log::channel('stdout')->info('Updating company:' . $company->id);

            $company->makeCurrent();

            $this->updateEmailTemplates();

            Log::channel('stdout')->info('Company updated:' . $company->id);
        }

        company($company_id)->makeCurrent();

        Log::channel('stdout')->info('Companies updated.');
    }

    public function updateEmailTemplates()
    {
        Log::channel('stdout')->info('Updating Email Templates...');

        $email_templates = EmailTemplate::cursor();

        foreach ($email_templates as $email_template) {
            Log::channel('stdout')->info('Updating email template:' . $email_template->id);

            $body = preg_replace('%<p(.*?)>|</p>%s', '', $email_template->body);

            $email_template->body = $body;

            $email_template->save();

            Log::channel('stdout')->info('Email Template updated:' . $email_template->id);
        }

        Log::channel('stdout')->info('Email Templates updated.');
    }

    public function deleteOldFiles()
    {
        Log::channel('stdout')->info('Deleting old files...');

        $files = [
            'app/Events/Auth/InvitationCreated.php',
            'app/Listeners/Auth/SendUserInvitation.php',
            'app/Listeners/Auth/DeleteUserInvitation.php',
        ];

        foreach ($files as $file) {
            File::delete(base_path($file));
        }

        Log::channel('stdout')->info('Old files deleted.');
    }
}
