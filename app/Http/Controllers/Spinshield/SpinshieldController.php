<?php

namespace App\Http\Controllers\Spinshield;

use spinshield\spinclient;
use App\Models\Gamelist;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class SpinshieldController
{
    protected $client;
    protected $helpers;
    protected $supportedCurrencies = ['USD', 'BRL', 'AUD', 'NZD', 'TRY', 'CAD', 'GBP', 'EUR'];

    /**
     * SpinshieldController constructor.
     * Initializes the API client and helper classes.
     */
    public function __construct()
    {
        $this->client = new spinclient\ApiClient([
            'endpoint' => config('spinshield.endpoint'),
            'api_login' => config('spinshield.api_login'),
            'api_password' => config('spinshield.api_password'),
        ]);
        $this->helpers = new spinclient\Helpers();
    }

    /**
     * Retrieve the list of available games for all supported currencies.
     *
     * @return Collection
     */
    public function gamelist(): Collection
    {
        $allGames = collect();

        foreach ($this->supportedCurrencies as $currency) {
            $response = $this->helpers->morphJsonToArray($this->client->getGameList($currency, 1));

            if (isset($response['response']) && is_array($response['response'])) {
                $games = collect($response['response'])->map(function ($game) use ($currency) {
                    $game['currency'] = $currency;
                    return $game;
                });

                $allGames = $allGames->concat($games);
                Log::info('Fetched ' . $games->count() . ' games for currency: ' . $currency);
            } else {
                Log::error("Failed to fetch games for currency: $currency");
            }
        }

        return $allGames->unique('id_hash');
    }

    public function deleteAllFreeRounds(User $user, $currency)
    {
        $currency = strtoupper($currency);
        $playerId = $user->id . '-' . $currency;
        $playerPassword = $playerId;

        $gameResponse = $this->client->deleteAllFreeRounds(
            $playerId,
            $playerPassword,
            $currency,
        );
        return true;
    }

    public function addFreeRounds(User $user, string $id_hash, $currency, int $freespinsAmount)
    {
        $currency = strtoupper($currency);
        $playerId = $user->id . '-' . $currency;
        $playerPassword = $playerId;

        $gameResponse = $this->client->addFreeRounds(
            $playerId,
            $playerPassword,
            $id_hash,
            $currency,
            $freespinsAmount,
            0,
        );
        return true;
    }

    /**
     * Start a game for the given user.
     *
     * @param User $user
     * @param string $id_hash
     * @param int $demo
     * @param string $currency
     * @return array
     */
    public function startGame(User $user, string $id_hash, int $demo, string $currency)
    {
        $currency = strtoupper($currency);
        $playerId = $user->id . '-' . $currency;
        $playerPassword = $playerId;

        // Create player on the spinshield API
        $this->client->createPlayer(
            $playerId,
            $playerPassword,
            $playerId,
            $currency,
        );

        // Open the specified game
        $gameResponse = $demo === 1
            ? $this->client->getGameDemo(
                $id_hash,
                $currency,
                config('spinshield.home_url'),
                config('spinshield.deposit_url'),
                config('spinshield.game_language'),
            )
            : $this->client->getGame(
                $playerId,
                $playerPassword,
                $id_hash,
                $currency,
                config('spinshield.home_url'),
                config('spinshield.deposit_url'),
                $demo,
                config('spinshield.game_language')
            );

        if ($this->helpers->responseHasError($gameResponse)) {
            Log::error('Error starting game: ' . json_encode($gameResponse));
            throw new \Exception('Failed to start game');
        }

        return $this->helpers->morphJsonToArray($gameResponse);
    }
}
