<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gamelist;

class TagAddToGamesByName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tags:add-to-games-by-name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a specified tag to all games matching a name pattern';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $namePattern = $this->ask('Enter the name pattern to search for games:');
        $tag = $this->ask('Enter the tag you wish to add:');

        // For databases that support ILIKE (like PostgreSQL)
        // $games = Gamelist::where('name', 'ILIKE', '%' . $namePattern . '%')->get();
        
        // For databases that don't support ILIKE (like MySQL)
        $games = Gamelist::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($namePattern) . '%'])->get();
        
        if ($games->isEmpty()) {
            $this->info("No games found matching the pattern '{$namePattern}'.");
            return 0;
        }

        foreach ($games as $game) {
            $game->attachTag($tag);
            $this->info("Tag '{$tag}' added to game: {$game->name}");
        }

        $this->info("Finished adding tags to matching games.");
        return 0;
    }
}
