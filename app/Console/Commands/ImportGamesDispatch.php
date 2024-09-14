<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ImportGamesFromSpinshield;

class ImportGamesDispatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-games-dispatch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch job to import games from Spinshield';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Dispatching job to import games from Spinshield...');
        ImportGamesFromSpinshield::dispatch();
        $this->info('Job dispatched successfully.');
        return 0;
    }
}
