<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

// this package uses this in it's base, so we will use directly the base package
// https://github.com/jolicode/JoliNotif
class DesktopNotificationTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:desktop-notification-test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->notify('Hello Web Artisan', 'Love beautiful code? We do too!');
    }
}
