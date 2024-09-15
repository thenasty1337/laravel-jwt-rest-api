<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class RandomGenerator
{
    protected $config;

    public function __construct()
    {
        $this->config = config('games');
    }

    public function generatePrivateSeed(): string
    {
        return bin2hex(random_bytes(32));
    }

    public function buildPrivateHash(string $seed): string
    {
        return hash('sha256', $seed);
    }

    public function generatePrivateSeedHashPair(): array
    {
        $seed = $this->generatePrivateSeed();
        $hash = $this->buildPrivateHash($seed);
        return compact('seed', 'hash');
    }

    public function getPublicSeed(): string
    {
        return Cache::remember('public_seed', now()->addMinutes(5), function () {
            try {
                $response = Http::get('https://drand.cloudflare.com/public/latest');
                if ($response->successful()) {
                    $data = $response->json();
                    return $data['randomness'];
                }
                throw new Exception("Failed to fetch public seed from drand.cloudflare.com");
            } catch (Exception $e) {
                Log::error("Error fetching public seed: " . $e->getMessage());
                // Fallback to a local random seed if the API fails
                return bin2hex(random_bytes(32));
            }
        });
    }

    protected function generateRandomFloat(string $seed, float $min, float $max, int $decimals = 8): float
    {
        $hash = hash('sha256', $seed);
        $integer = hexdec(substr($hash, 0, 13)); // Use first 13 hex chars (52 bits)
        $float = $integer / (2 ** 52); // Convert to float between 0 and 1
        return round($min + ($float * ($max - $min)), $decimals);
    }

    public function generateRandomInt(string $privateSeed, int $min, int $max): int
    {
        $publicSeed = $this->getPublicSeed();
        $combinedSeed = "{$privateSeed}-{$publicSeed}";
        return (int) $this->generateRandomFloat($combinedSeed, $min, $max + 1);
    }

    public function generateCoinflipRandom(string $gameId, string $privateSeed): array
    {
        $publicSeed = $this->getPublicSeed();
        $combinedSeed = "{$privateSeed}-{$gameId}-{$publicSeed}";
        $module = $this->generateRandomFloat($combinedSeed, 0, 100, 7);
        return [
            'publicSeed' => $publicSeed,
            'module' => $module,
            'result' => $module < 50 ? 'heads' : 'tails',
            'verificationHash' => $this->buildPrivateHash($combinedSeed)
        ];
    }

    public function generateJackpotRandom(string $gameId, string $privateSeed, int $maxTicket): array
    {
        $publicSeed = $this->getPublicSeed();
        $combinedSeed = "{$gameId}-{$privateSeed}-{$publicSeed}";
        $randomModule = $this->generateRandomFloat($combinedSeed, 0, 100, 7);
        $winningTicket = min(round($maxTicket * ($randomModule / 100)), $maxTicket);
        return [
            'module' => $randomModule,
            'winningTicket' => $winningTicket,
            'publicSeed' => $publicSeed,
            'verificationHash' => $this->buildPrivateHash($combinedSeed)
        ];
    }

    public function generateRouletteRandom(string $gameId, string $privateSeed, string $publicSeed, int $roundNumber): array
    {
        $combinedSeed = "{$privateSeed}-{$gameId}-{$publicSeed}-{$roundNumber}";
        $module = $this->generateRandomInt($combinedSeed, 0, 36);
        return [
            'publicSeed' => $publicSeed,
            'module' => $module,
            'verificationHash' => $this->buildPrivateHash($combinedSeed)
        ];
    }

    public function generateCrashRandom(string $privateSeed): array
    {
        $publicSeed = $this->getPublicSeed();
        $crashPoint = $this->generateCrashPoint($privateSeed, $publicSeed);
        return [
            'publicSeed' => $publicSeed,
            'crashPoint' => $crashPoint,
            'verificationHash' => $this->buildPrivateHash("{$privateSeed}-{$publicSeed}")
        ];
    }

    protected function generateCrashPoint(string $seed, string $salt): float
    {
        $hash = hash_hmac('sha256', $salt, $seed);
        $h = hexdec(substr($hash, 0, 13));
        $e = 2 ** 52;

        $houseEdge = $this->config['crash']['houseEdge'];
        $result = 100 * $e / ($e - $h);

        // Apply house edge
        $result *= (1 - $houseEdge);

        // Limit to 2 decimal places and ensure minimum of 1.00
        return max(1.00, round($result, 2));
    }

    public function generateDiceRandom(string $privateSeed, string $rollType, float $rollChance): array
    {
        $publicSeed = $this->getPublicSeed();
        $combinedSeed = "{$privateSeed}-{$publicSeed}";
        $roll = $this->generateRandomFloat($combinedSeed, 0, 100, 2);
        $winCondition = $rollType === 'above' ? 100 - $rollChance : $rollChance;
        $isWin = $rollType === 'above' ? $roll > $winCondition : $roll < $winCondition;
        return [
            'roll' => $roll,
            'isWin' => $isWin,
            'publicSeed' => $publicSeed,
            'verificationHash' => $this->buildPrivateHash($combinedSeed)
        ];
    }

    public function generateWhipPID(array $players, string $privateSeed): array
    {
        $publicSeed = $this->getPublicSeed();
        $highestPID = 0;
        $highestPIDPlayer = null;

        foreach ($players as &$player) {
            $seed = "{$privateSeed}-{$player['clientSeed']}-{$publicSeed}";
            $pid = $this->generateRandomInt($seed, 0, 9999);
            $player['pid'] = [
                'seed' => $seed,
                'pid' => $pid,
                'verificationHash' => $this->buildPrivateHash($seed)
            ];

            if ($pid > $highestPID) {
                $highestPID = $pid;
                $highestPIDPlayer = $player;
            }
        }

        return [
            'highestPID' => $highestPID,
            'highestPIDPlayer' => $highestPIDPlayer,
            'players' => $players,
            'publicSeed' => $publicSeed,
            'verificationHash' => $this->buildPrivateHash("{$privateSeed}-{$publicSeed}")
        ];
    }

    public function verifyResult(string $privateSeed, string $publicSeed, string $verificationHash): bool
    {
        return $this->buildPrivateHash("{$privateSeed}-{$publicSeed}") === $verificationHash;
    }
}
