<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateMonthLimits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-month-limits';

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
        $users = User::where('paid', 0)->get();
        foreach ($users as $user) {
            $user->month_limit = 10;
            $user->save();
        }

        $paidUsers = User::where('paid', 1)->get();
        foreach ($paidUsers as $user) {
            $user->month_limit = 200;
            $user->save();
        }
    }
}
