<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gamelist;

class IndexRatingAssignToNew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:assign-to-new';

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
        $games = Gamelist::where("index_rating", 255)->get();
        
        foreach ($games as $game) {
            $indexRating = (int) ((rand(50, 125)) - $this->addedIndexRating($game['category']));
            Gamelist::where("id_hash", $game['id_hash'])->update([
                "index_rating" => $indexRating,
            ]);
        }

        $this->info("Finished setting index rating");
        return 0;
    }
    
    public function addedIndexRating($provider) {
        if($provider === "hacksaw") {
            return 5;
        }
        if($provider === "bgaming") {
            return 4;
        }
        if($provider === "pragmaticplay") {
            return 0;
        }
        if($provider === "onlyplay") {
            return 7;
        }
        if($provider === "caleta") {
            return 3;
        }
        if($provider === "wizard") {
            return 5;
        }
        if($provider === "yggdrasil") {
            return 5;
        }
        if($provider === "gamebeat") {
            return 5;
        }

        return 0;
    }
}
