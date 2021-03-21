<?php

namespace Akaunting\Firewall\Commands;

use Akaunting\Firewall\Models\Ip;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UnblockIp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firewall:unblockip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unblock ips based on their block period';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now(config('app.timezone'));

        $ip = config('firewall.models.ip', Ip::class);
        $ip::with('log')->blocked()->each(function ($ip) use ($now) {
            if (empty($ip->log)) {
                return;
            }

            $period = config('firewall.middleware.' . $ip->log->middleware . '.auto_block.period');

            if ($ip->created_at->addSeconds($period) > $now) {
                return;
            }

            $ip->logs()->delete();
            $ip->delete();
        });
    }
}
