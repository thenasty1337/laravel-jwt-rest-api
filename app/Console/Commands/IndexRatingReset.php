<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gamelist;

class IndexRatingReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:rating-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign randomized index rating to new games';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $games = Gamelist::where("index_rating", ">", 0)->update(["index_rating" => 255]);
        
        $this->info("Finished resetting index rating");
        return 0;
    }
}
