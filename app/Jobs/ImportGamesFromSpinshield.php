<?php

namespace App\Jobs;

use App\Models\Gamelist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Spinshield\SpinshieldController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

class ImportGamesFromSpinshield implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $games = $this->fetchGames();
            $this->processGames($games);
            $this->deactivateRemovedGames($games);

            Log::info('Games import completed successfully.');
        } catch (\Exception $e) {
            Log::error('Error importing games: ' . $e->getMessage());
            $this->fail($e);
        }
    }

    /**
     * Fetch games from Spinshield.
     *
     * @return \Illuminate\Support\Collection
     */
    private function fetchGames()
    {
        $games = (new SpinshieldController)->gamelist();
        Log::info('Fetched ' . $games->count() . ' games from Spinshield.');
        return $games;
    }

    /**
     * Process and update or create games.
     *
     * @param \Illuminate\Support\Collection $games
     */
    private function processGames($games)
    {
        $games->chunk(100)->each(function ($chunk) {
            $chunk->each(function ($attributes) {
                Gamelist::updateOrCreate(
                    ['id_hash' => $attributes['id_hash']],
                    [
                        'name' => $attributes['name'],
                        'type' => $attributes['type'],
                        'category' => $attributes['category'],
                        'image' => $attributes['image'],
                        'image_portrait' => $attributes['image_portrait'],
                        'freerounds_supported' => $attributes['freerounds_supported'],
                        'new' => $attributes['new'],
                        'active' => true,
                        'currency' => $attributes['currency'], // Add this line to store the currency
                    ]
                );
            });
        });
    }

    /**
     * Deactivate games that are no longer in the Spinshield list.
     *
     * @param \Illuminate\Support\Collection $activeGames
     */
    private function deactivateRemovedGames($activeGames)
    {
        $activeIdHashes = $activeGames->pluck('id_hash')->unique()->toArray();

        LazyCollection::make(function () {
            foreach (Gamelist::cursor() as $game) {
                yield $game;
            }
        })->chunk(100)->each(function ($chunk) use ($activeIdHashes) {
            $chunk->each(function ($game) use ($activeIdHashes) {
                if (!in_array($game->id_hash, $activeIdHashes)) {
                    $game->update(['active' => false]);
                }
            });
        });
    }
}
