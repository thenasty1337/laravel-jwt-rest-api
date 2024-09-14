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
        $data = collect((new SpinshieldController)->gamelist()['response']);
        Log::info($data);

        // update gamelist entry or create if doesnt exist
        $data->each(function ($attributes) {
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
                ]
            );
        });

        // sync backwards
        foreach (Gamelist::all() as $game) {
            $selectGame = $data->where("id_hash", $game['id_hash'])->first();
            if (!$selectGame) {
                Gamelist::where("id_hash", $game['id_hash'])->update(["active" => false]);
            }
        }

    }
}
