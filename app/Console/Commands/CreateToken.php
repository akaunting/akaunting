<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\WbProxy\Models\Auth\User;

class CreateToken extends Command
{
    public $user;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanctum:token {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate users token';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->user = User::find($this->argument('user'));

        $token = $this->user->createToken('Api Token');

        $this->info("token => {$token->plainTextToken}");

        return self::SUCCESS;
    }
}
