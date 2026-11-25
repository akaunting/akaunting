<?php

namespace Tests\Feature\Common;

use App\Http\Livewire\Menu\Notifications;
use App\Jobs\Auth\CreateUser;
use App\Models\Common\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Feature\FeatureTestCase;

class UploadsAndNotificationsTest extends FeatureTestCase
{
    public function testItShouldNotClearAnotherSettingWhenAFileIsDeleted()
    {
        $logo = $this->uploadLogo();

        setting(['company.email' => 'company@example.com'])->save();

        $this->loginAs()
            ->deleteJson(route('uploads.destroy', $logo), ['ajax' => 1, 'page' => 'setting', 'key' => 'company.email'])
            ->assertOk();

        $this->assertSame('company@example.com', setting('company.email'));
    }

    public function testItShouldStillClearTheSettingOfTheDeletedFile()
    {
        $logo = $this->uploadLogo();

        $this->loginAs()
            ->deleteJson(route('uploads.destroy', $logo), ['ajax' => 1, 'page' => 'setting', 'key' => 'company.logo'])
            ->assertOk();

        $this->assertEmpty(setting('company.logo'));
    }

    public function testItShouldUploadAFileAgainAfterItIsDeleted()
    {
        $deleted = Media::find($this->uploadLogo());

        $this->loginAs()
            ->deleteJson(route('uploads.destroy', $deleted->id), ['ajax' => 1, 'page' => 'setting', 'key' => 'company.logo'])
            ->assertOk();

        // The same name on the same day, which the deleted record still holds
        $media = Media::find($this->uploadLogo());

        $this->assertNotNull($media);
        $this->assertNotSame($deleted->filename, $media->filename);
        $this->assertStringStartsWith('logo', $media->filename);
        $this->assertTrue($media->fileExists());
    }

    public function testItShouldMarkOnlyTheOwnNotificationsRead()
    {
        $this->loginAs();

        $other = $this->dispatch(new CreateUser(array_merge(user_model_class()::factory()->raw(), ['enabled' => 1])));

        $theirs = $this->createNotification($other);
        $mine = $this->createNotification($this->user);

        Livewire::test(Notifications::class)->call('markRead', 'notification', $theirs->id);

        $this->assertNull($theirs->fresh()->read_at);

        Livewire::test(Notifications::class)->call('markRead', 'notification', $mine->id);

        $this->assertNotNull($mine->fresh()->read_at);
    }

    protected function uploadLogo()
    {
        $this->loginAs()
            ->post(route('wizard.companies.update'), [
                'financial_start' => '01-04',
                'country' => 'US',
                'logo' => UploadedFile::fake()->image('logo.jpg', 100, 100),
            ])
            ->assertOk();

        setting()->forgetAll();
        setting()->load(true);

        return setting('company.logo');
    }

    protected function createNotification($user): DatabaseNotification
    {
        return DatabaseNotification::create([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\Common\ExportCompleted',
            'notifiable_type' => $user->getMorphClass(),
            'notifiable_id' => $user->id,
            'data' => ['file_name' => 'export.xlsx', 'title' => 'Export', 'description' => ''],
        ]);
    }
}
