<?php

namespace App\Http\Controllers\Spinshield;

use spinshield\spinclient;
use App\Models\Gamelist;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Spinshield\SpinshieldController;
use Illuminate\Support\Facades\Log;

class LaunchGameController
{
    protected $helpers;
    protected $spinshield_controller;

    /**
     * LaunchGameController constructor.
     * Initializes the API client and helper classes.
     */
    public function __construct()
    {
        $this->spinshield_controller = new SpinshieldController;
        $this->helpers = new spinclient\Helpers();
        Log::info('LaunchGameController instantiated');
    }

    /**
     * Launch a game based on the provided request data.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function launch(Request $request)
    {
        Log::info('Game launch initiated', ['request' => $request->all()]);

        // Validate the request inputs
        try {
            $validated = $request->validate([
                'id_hash' => 'required|max:255',
                'currency' => 'required|max:3',
                'play_for_fun' => 'required|integer|max:1',
            ]);
            Log::info('Request validation passed', ['validated' => $validated]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Request validation failed', ['errors' => $e->errors()]);
            throw $e;
        }

        // Retrieve the authenticated user
        $user = $request->user();
        $currency = strtoupper($request->currency);
        Log::info('User and currency retrieved', ['user_id' => $user->id, 'currency' => $currency]);

        // Find the game based on the provided id_hash or fail if not found
        try {
            $game = Gamelist::where('id_hash', $request->id_hash)->firstOrFail();
            Log::info('Game found', ['game' => $game->toArray()]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Game not found', ['id_hash' => $request->id_hash]);
            throw $e;
        }

        // Start the game using the SpinshieldController
        if ($user->freespins_active === true && $game->freerounds_supported === true) {
            Log::info('Free spins active and supported for this game', [
                'user_id' => $user->id,
                'game_id' => $game->id,
                'freespins_total' => $user->freespins_total,
                'freespins_performed' => $user->freespins_performed
            ]);

            $currency = config("spinshield.freespins_currency");
            $freespinsAmount = (int) ($user->freespins_total - $user->freespins_performed);

            Log::info('Deleting all free rounds for user');
            $this->spinshield_controller->deleteAllFreeRounds($user, $currency);

            Log::info('Adding new free rounds', ['amount' => $freespinsAmount]);
            $this->spinshield_controller->addFreeRounds($user, $request->id_hash, $currency, $freespinsAmount);

            $user->update([
                "freespins_performed_state" => 0,
            ]);
            Log::info('User freespins_performed_state reset to 0');
        } else {
            Log::info('No free spins active or not supported for this game', [
                'user_id' => $user->id,
                'game_id' => $game->id,
                'freespins_active' => $user->freespins_active,
                'freerounds_supported' => $game->freerounds_supported
            ]);
        }

        Log::info('Starting game', [
            'user_id' => $user->id,
            'game_id_hash' => $request->id_hash,
            'play_for_fun' => $request->play_for_fun,
            'currency' => $currency
        ]);
        $response = $this->spinshield_controller->startGame($user, $request->id_hash, $request->play_for_fun, $currency);

        // Check for errors in the response and return an error response if any
        if ($response['error'] !== 0) {
            Log::error('Error starting game', ['response' => $response]);
            return response(["name" => $game->name, "category" => $game->category, 'error' => $response], 400);
        }

        Log::info('Game started successfully', ['game_url' => $response['response']]);

        // Redirect to the game URL provided in the response
        return [
            "error" => 0,
            "name" => $game->name,
            "category" => $game->category,
            "url" => $response['response'],
        ];
    }
}
