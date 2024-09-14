<?php

namespace App\Http\Controllers\Spinshield;

use spinshield\spinclient;
use App\Models\Gamelist;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SpinshieldController
{
    protected $client;
    protected $helpers;

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
     * Retrieve the list of available games.
     *
     * getGameList: https://documentation.spin.ac/api-methods/gamelist
     *
     * @return array
     */
    public function gamelist()
    {
        return $this->helpers->morphJsonToArray($this->client->getGameList('USD', 1));
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
     * createPlayer: https://documentation.spin.ac/api-methods/create-player
     * getGame: https://documentation.spin.ac/api-methods/get-game
     *
     * Supported languages: fr en de tr ru nl pt es
     * Supported currencies: USD BRL AUD NZD TRY CAD GBP EUR
     *
     * @param User $user
     * @param string $id_hash
     * @param int $demo
     * @return array
     */
    public function startGame(User $user, string $id_hash, int $demo, $currency)
    {

        $currency = strtoupper($currency);
        $playerId = $user->id . '-' . $currency;
        $playerPassword = $playerId;
        // Create player on the spinshield API - should always be called before opening game

        $this->client->createPlayer(
            $playerId,
            $playerPassword,
            $playerId,
            $currency,
        );

        // Open the specified game
        if ($demo === 1) {
            $gameResponse = $this->client->getGameDemo(
                $id_hash,
                $currency,
                config('spinshield.home_url'),
                config('spinshield.deposit_url'),
                config('spinshield.game_language'),
            );
        } else {
            $gameResponse = $this->client->getGame(
                $playerId,
                $playerPassword,
                $id_hash,
                $currency,
                config('spinshield.home_url'),
                config('spinshield.deposit_url'),
                $demo,
                config('spinshield.game_language')
            );
        }


        if ($this->helpers->responseHasError($gameResponse)) {
            // insert additional error handling/logic
        }

        return $this->helpers->morphJsonToArray($gameResponse);
    }
}
