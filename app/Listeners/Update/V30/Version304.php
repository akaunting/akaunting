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

        Log::channel('stderr')->info('Starting the Akaunting 3.0.4 update...');

        $this->updateDatabase();

        $this->updateCompanies();

        $this->deleteOldFiles();

        Log::channel('stderr')->info('Akaunting 3.0.4 update finished.');
    }

    public function updateDatabase()
    {
        Log::channel('stderr')->info('Updating database...');

        Artisan::call('migrate', ['--force' => true]);

        Log::channel('stderr')->info('Database updated.');
    }

    public function updateCompanies()
    {
        Log::channel('stderr')->info('Updating companies...');

        $company_id = company_id();

        $companies = Company::cursor();

        foreach ($companies as $company) {
            Log::channel('stderr')->info('Updating company:' . $company->id);

            $company->makeCurrent();

            $this->updateEmailTemplates();

            Log::channel('stderr')->info('Company updated:' . $company->id);
        }

        company($company_id)->makeCurrent();

        Log::channel('stderr')->info('Companies updated.');
    }

    public function updateEmailTemplates()
    {
        Log::channel('stderr')->info('Updating Email Templates...');

        $email_templates = EmailTemplate::cursor();

        foreach ($email_templates as $email_template) {
            Log::channel('stderr')->info('Updating email template:' . $email_template->id);

            $body = preg_replace('%<p(.*?)>|</p>%s', '', $email_template->body);

            $email_template->body = $body;

            $email_template->save();

            Log::channel('stderr')->info('Email Template updated:' . $email_template->id);
        }

        Log::channel('stderr')->info('Email Templates updated.');
    }

    public function deleteOldFiles()
    {
        Log::channel('stderr')->info('Deleting old files...');

        $files = [
            'app/Events/Auth/InvitationCreated.php',
            'app/Listeners/Auth/SendUserInvitation.php',
            'app/Listeners/Auth/DeleteUserInvitation.php',
        ];

        foreach ($files as $file) {
            File::delete(base_path($file));
        }

        Log::channel('stderr')->info('Old files deleted.');
    }
}
