<?php

namespace App\Observers;

use App\Models\Auth\User as Model;

class User
{
    /**
     * Listen to the deleted event.
     *
     * @param  Model  $user
     * @return void
     */
    public function deleted(Model $user)
    {
        $tables = [
            'dashboards',
        ];

        foreach ($tables as $table) {
            $this->deleteItems($user, $table);
        }
    }

    /**
     * Delete items in batch.
     *
     * @param  Model  $user
     * @param  $table
     * @return void
     */
    protected function deleteItems($user, $table)
    {
        foreach ($user->$table as $item) {
            $item->delete();
        }
    }
}
