<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;

class DeleteReport extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        \DB::transaction(function () {
            $this->deleteFavorite();
            $this->deletePin();

            $this->model->delete();
        });

        return true;
    }

    public function deleteFavorite()
    {
        $favorites = setting('favorites.menu', []);
        
        if (empty($favorites)) {
            return;
        }

        foreach ($favorites as $user_id => $user_favorites) {
            $user_favorites = json_decode($user_favorites, true);

            foreach ($user_favorites as $key => $favorite) {
                if (! is_array($favorite['route'])) {
                    continue;
                }

                if (str_contains($favorite['route'][0], 'reports.show') && $this->model->id == $favorite['route'][1]) {
                    unset($user_favorites[$key]);
                }
            }

            setting()->set('favorites.menu.' . $user_id, json_encode($user_favorites));
            setting()->save();
        }
    }

    public function deletePin()
    {
        $pins = setting('favorites.report', []);

        if (empty($pins)) {
            return;
        }

        foreach ($pins as $user_id => $user_pins) {
            $user_pins = json_decode($user_pins, true);

            foreach ($user_pins as $key => $pin) {
                if ($this->model->id == $pin) {
                    unset($user_pins[$key]);

                    break;
                }
            }

            setting()->set('favorites.report.' . $user_id, json_encode($user_pins));
            setting()->save();
        }
    }
}
