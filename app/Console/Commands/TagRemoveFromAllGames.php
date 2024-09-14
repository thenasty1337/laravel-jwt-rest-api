<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gamelist;

class TagRemoveFromAllGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tags:remove-from-all-games';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a specified tag from all games';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $tag = $this->ask('Enter the tag you wish to remove:');

        $games = Gamelist::all();
        
        if ($games->isEmpty()) {
            $this->info("No games found.");
            return 0;
        }

        foreach ($games as $game) {
            $game->detachTag($tag);
            $this->info("Tag '{$tag}' removed from game: {$game->name}");
        }

        $this->info("Finished removing the tag from all games.");
        return 0;
    }
}
