<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TransfertSendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfert:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get transfert stored and make transfert';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        info("Send transfert");
        sleep(3);
        return 0;
    }
}
