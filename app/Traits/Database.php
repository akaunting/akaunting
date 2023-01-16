<?php

namespace App\Traits;

trait Database
{
    public function databaseDriverIs(string $driver): bool
    {
        $connection = config('database.default');

        return config("database.connections.$connection.driver") === $driver;
    }
}
