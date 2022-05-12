<?php

namespace App\Console\Commands;

use App\Http\Controllers\TransfertController;
use Illuminate\Console\Command;

class TransfertStoreCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfert:store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get transfert online and store';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        TransfertController::store();
        return 0;
    }
}
