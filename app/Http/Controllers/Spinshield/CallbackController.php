<?php

namespace App\Http\Controllers\Spinshield;

use Illuminate\Http\Request;
use App\Models\Gamelist;
use App\Models\User;
use App\Models\Wallet;
use spinshield\spinclient;
use Illuminate\Support\Facades\Log;

class CallbackController
{
    protected $salt;
    protected $helpers;
    protected $requestData;

    /**
     * Constructor to initialize properties.
     */
    public function __construct()
    {
        $this->salt = config("spinshield.salt");
        $this->helpers = new spinclient\Helpers();
        $this->requestData = [];
        Log::info('CallbackController instantiated');
    }

    /**
     * Router method to handle different actions (debit, credit, balance) based on request data.
     *
     * @param Request $request
     * @return mixed
     */
    public function router(Request $request)
    {
        try {
            $this->requestData = $request->all();
            Log::info('Received request data:', $this->requestData);

            if (!$this->helpers->isValidKey($this->requestData['key'], $this->requestData['timestamp'], $this->salt)) {
                Log::warning('Invalid key detected');
                return $this->helpers->processingError();
            }

            $explodeUsername = explode('-', $this->requestData['username']);
            $userUuid = $explodeUsername[0];
            $this->currencyType = strtolower($explodeUsername[1]);
            Log::info("Processing request for user ID: {$userUuid}, Currency: {$this->currencyType}");

            $user = User::where('id', $userUuid)->first();
            if (!$user) {
                Log::error("User not found for UUID: {$userUuid}");
                return $this->helpers->processingError();
            }

            Log::info("User found: {$user->id}");

            switch ($this->requestData['action']) {
                case "debit":
                    Log::info("Initiating debit action");
                    return $this->debitWallet($user);
                case "credit":
                    Log::info("Initiating credit action");
                    return $this->creditWallet($user);
                case "balance":
                    Log::info("Initiating balance check");
                    return $this->helpers->balanceResponse($this->getWalletBalance($user));
                default:
                    Log::warning("Unknown action received: {$this->requestData['action']}");
                    return $this->helpers->processingError();
            }

        } catch (\Exception $e) {
            Log::critical("CallbackController error: {$e->getMessage()} on line {$e->getLine()}", [
                'exception' => $e,
                'request_data' => $this->requestData
            ]);
            return $this->helpers->processingError();
        }
    }

    /**
     * Get the wallet balance of a user.
     *
     * @param User $user
     * @return int
     */
    public function getWalletBalance(User $user)
    {
        Log::info("Getting wallet balance for user: {$user->id}, Currency: {$this->currencyType}");

        $wallet = Wallet::where('user_id', $user->uuid)
            ->where('currency', $this->currencyType)
            ->first();

        if (!$wallet) {
            Log::info("No wallet found for user: {$user->id}, Currency: {$this->currencyType}");
            return 0;
        }

        $balance = $this->helpers->floatToIntHelper(
            number_format((float) $wallet->balance, 2, '.', '')
        );

        Log::info("Wallet balance for user {$user->id}: {$balance}");
        return $balance;
    }

    /**
     * Debit an amount from the user's wallet.
     *
     * @param User $user
     * @return mixed
     */
    public function debitWallet(User $user)
    {
        Log::info("Debit operation started for user: {$user->id}");

        $balanceBefore = $this->getWalletBalance($user);
        $amountToDebit = (int) $this->requestData['amount'];
        $balanceAfter = $balanceBefore - $amountToDebit;

        Log::info("Debit details: Balance Before: {$balanceBefore}, Amount to Debit: {$amountToDebit}, Balance After: {$balanceAfter}");

        // balance not to be deducted when type is "bonus_fs", this equals gifted free rounds
        if ($this->requestData['type'] === "bonus_fs") {
            Log::info("Bonus free spins detected, updating user state");

            $performedState = $user->freespins_performed_state;
            $performedCurrently = $this->requestData['freespins']['performed'];

            Log::info("Current performed state: {$performedState}, New performed state: {$performedCurrently}");

            if ($performedState !== $performedCurrently) {
                $userPerformed = $user->freespins_performed + 1;
                $user->update(["freespins_performed_state" => $this->requestData['freespins']['performed']]);
                $user->update(["freespins_performed" => $userPerformed]);

                Log::info("Updated user free spins performed: {$userPerformed}");

                if ($userPerformed >= $user->freespins_total) {
                    Log::info("All free spins used, resetting user free spins state");
                    $user->update([
                        "freespins_active" => false,
                        "freespins_total" => 0,
                        "freespins_performed" => 0,
                    ]);
                }
            }

            return $this->helpers->balanceResponse($balanceBefore);
        }

        if ($balanceAfter < 0) {
            Log::warning("Insufficient balance for user: {$user->id}. Balance: {$balanceBefore}, Attempted debit: {$amountToDebit}");
            return $this->helpers->insufficientBalance($balanceBefore);
        }

        $wallet = Wallet::where('user_id', $user->uuid)
            ->where('currency', $this->currencyType)
            ->first();

        if ($wallet) {
            $wallet->update([
                'balance' => $this->helpers->intToFloatHelper($balanceAfter)
            ]);
            Log::info("Wallet updated for user: {$user->id}. New balance: {$balanceAfter}");
        } else {
            Log::error("Wallet not found for user: {$user->id}, Currency: {$this->currencyType}");
        }

        return $this->helpers->balanceResponse($balanceAfter);
    }

    /**
     * Credit an amount to the user's wallet.
     *
     * @param User $user
     * @return mixed
     */
    public function creditWallet(User $user)
    {
        Log::info("Credit operation started for user: {$user->id}");

        $balanceBefore = $this->getWalletBalance($user);
        $amountToCredit = (int) $this->requestData['amount'];
        $balanceAfter = $balanceBefore + $amountToCredit;

        Log::info("Credit details: Balance Before: {$balanceBefore}, Amount to Credit: {$amountToCredit}, Balance After: {$balanceAfter}");

        $wallet = Wallet::where('user_id', $user->uuid)
            ->where('currency', $this->currencyType)
            ->first();

        if ($wallet) {
            $wallet->update([
                'balance' => $this->helpers->intToFloatHelper($balanceAfter)
            ]);
            Log::info("Existing wallet updated for user: {$user->id}. New balance: {$balanceAfter}");
        } else {
            Wallet::create([
                'user_id' => $user->uuid,
                'currency' => $this->currencyType,
                'balance' => $this->helpers->intToFloatHelper($balanceAfter)
            ]);
            Log::info("New wallet created for user: {$user->id}. Initial balance: {$balanceAfter}");
        }

        return $this->helpers->balanceResponse($balanceAfter);
    }
}
