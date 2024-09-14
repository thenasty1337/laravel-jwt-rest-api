<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gamelist;

class TagRemoveFromGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tags:remove-from-game';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a tag from a game';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $id_hash = $this->ask('What is the game id_hash?', 'softswiss/whatever');

        $selectGame = Gamelist::where("id_hash", $id_hash)->first();
        if(!$selectGame) {
            $this->info("Error finding game with that id.");
            return 0;
        }

        $tag = $this->ask('Tag you wish to remove?');
        $selectGame->detachTag($tag);
        
        $this->info('Removed tag, printing all of game\'s current tags:');
        foreach($selectGame->tags as $tag) {
            $this->info("- Current Tag: {$tag['name']}");
        }

        return 0;
    }
}
