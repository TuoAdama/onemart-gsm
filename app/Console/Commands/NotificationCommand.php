<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permet de notifier le serveur en ligne';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return 0;
    }
}
