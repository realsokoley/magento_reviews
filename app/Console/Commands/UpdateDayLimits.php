<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateDayLimits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-day-limits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $users = User::where('paid', 0);
        foreach ($users as $user) {
            $user->day_limit = 1;
            $user->save();
        }

        $paidUsers = User::where('paid', 1);
        foreach ($paidUsers as $user) {
            $user->day_limit = 100;
            $user->save();
        }
    }
}
