<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gamelist;

class TagSyncGamelist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tags:sync-gamelist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the new tag on all games';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->newGames();
        $this->freeRounds();
        return 0;
    }

    public function freeRounds()
    {
        $tag = "freerounds";
        $taggedGames = Gamelist::withAnyTags($tag)->get();
        $allGames = Gamelist::all();

        foreach ($taggedGames as $game) {
            $game->detachTag($tag);
        }

        foreach ($allGames as $game) {
            if($game->freerounds_supported === true) {
                $game->attachTag($tag);
                $this->info("Tag '{$tag}' added to game: {$game->name}");
            }
        }

        $this->info("Finished syncing {$tag} game tag.");
    }

    public function newGames()
    {
        $tag = "new";
        $taggedGames = Gamelist::withAnyTags($tag)->get();
        $allGames = Gamelist::all();

        foreach ($taggedGames as $game) {
            $game->detachTag($tag);
        }

        foreach ($allGames as $game) {
            if($game->new === true) {
                $game->attachTag($tag);
                $this->info("Tag '{$tag}' added to game: {$game->name}");
            }
        }

        $this->info("Finished syncing {$tag} game tag.");
    }
}
