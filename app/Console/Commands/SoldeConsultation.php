<?php

namespace App\Console\Commands;

use App\Http\Controllers\SoldeController;
use Illuminate\Console\Command;

class SoldeConsultation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'solde:consultation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SoldeController::SoldeActuel();
        return 0;
    }
}
