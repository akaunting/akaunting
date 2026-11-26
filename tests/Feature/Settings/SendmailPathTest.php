<?php

namespace Tests\Feature\Settings;

use App\Utilities\Overrider;
use Tests\Feature\FeatureTestCase;

class SendmailPathTest extends FeatureTestCase
{
    public function testItShouldNotSaveACommandAsTheSendmailPath()
    {
        foreach (["sh -c 'id>/tmp/x' -t", '/usr/sbin/sendmail -t; id', '/usr/sbin/sendmail -t | id', '$(id)'] as $path) {
            $this->loginAs()
                ->withExceptionHandling()
                ->patchJson(route('settings.email.update'), [
                    '_prefix' => 'email',
                    'protocol' => 'sendmail',
                    'sendmail_path' => $path,
                ])
                ->assertStatus(422)
                ->assertJsonValidationErrors('sendmail_path');
        }

        // Nor through the rules of another page
        $this->loginAs()
            ->withExceptionHandling()
            ->patchJson(route('settings.email.update'), [
                '_prefix' => 'company',
                'protocol' => 'sendmail',
                'sendmail_path' => "sh -c 'id' -t",
            ])
            ->assertStatus(422);

        $this->assertNotSame("sh -c 'id' -t", setting('email.sendmail_path'));
    }

    public function testItShouldStillSaveASendmailPath()
    {
        foreach (['/usr/sbin/sendmail -bs', '/usr/sbin/sendmail -t -i', '/usr/local/bin/msmtp'] as $path) {
            $this->loginAs()
                ->patchJson(route('settings.email.update'), [
                    '_prefix' => 'email',
                    'protocol' => 'sendmail',
                    'sendmail_path' => $path,
                ])
                ->assertOk()
                ->assertJson(['success' => true]);

            $this->assertSame($path, setting('email.sendmail_path'));
        }
    }

    public function testItShouldNotRunASendmailPathSavedBefore()
    {
        $this->loginAs();

        $default = config('mail.mailers.sendmail.path');

        setting(['email.protocol' => 'sendmail', 'email.sendmail_path' => "sh -c 'id' -t"])->save();

        Overrider::load('settings');

        $this->assertSame($default, config('mail.mailers.sendmail.path'));

        setting(['email.sendmail_path' => '/usr/sbin/sendmail -t -i'])->save();

        Overrider::load('settings');

        $this->assertSame('/usr/sbin/sendmail -t -i', config('mail.mailers.sendmail.path'));
    }
}
