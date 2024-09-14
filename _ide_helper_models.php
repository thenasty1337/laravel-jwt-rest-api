<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $user_id
 * @property string $wallet_id
 * @property string $currency
 * @property string $amount
 * @property string $transaction_hash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Wallet $wallet
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereTransactionHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereWalletId($value)
 */
	class CryptoTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $uuid
 * @property string $symbol
 * @property string $name
 * @property string $name_full
 * @property string|null $max_supply
 * @property string|null $icon_url
 * @property string|null $usd_rate
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CryptocurrencyBlockchain> $blockchains
 * @property-read int|null $blockchains_count
 * @method static \Illuminate\Database\Eloquent\Builder|Cryptocurrency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cryptocurrency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cryptocurrency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cryptocurrency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cryptocurrency whereIconUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cryptocurrency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cryptocurrency whereMaxSupply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cryptocurrency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cryptocurrency whereNameFull($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cryptocurrency whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cryptocurrency whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cryptocurrency whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cryptocurrency whereUsdRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cryptocurrency whereUuid($value)
 */
	class Cryptocurrency extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $blockchain
 * @property string $network
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cryptocurrency|null $cryptocurrency
 * @method static \Illuminate\Database\Eloquent\Builder|CryptocurrencyBlockchain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CryptocurrencyBlockchain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CryptocurrencyBlockchain query()
 * @method static \Illuminate\Database\Eloquent\Builder|CryptocurrencyBlockchain whereBlockchain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptocurrencyBlockchain whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptocurrencyBlockchain whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptocurrencyBlockchain whereNetwork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptocurrencyBlockchain whereUpdatedAt($value)
 */
	class CryptocurrencyBlockchain extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $uuid
 * @property string $currency_from
 * @property string $currency_to
 * @property string $rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereCurrencyFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereCurrencyTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereUuid($value)
 */
	class ExchangeRate extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Bet
 *
 * @mixin Builder
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $id_hash
 * @property string $category
 * @property string $image
 * @property string $image_portrait
 * @property boolean $freerounds_supported
 * @property boolean $freerounds_supported
 * @property boolean $new
 * @property boolean $active
 * @property string $created_at
 * @property string $updated_at
 * @property bool $play_for_fun_supported
 * @property int $index_rating
 * @property bool $popular
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist whereFreeroundsSupported($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist whereIdHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist whereImagePortrait($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist whereIndexRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist whereNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist wherePlayForFunSupported($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist wherePopular($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Gamelist withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 */
	class Gamelist extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $message
 * @property int|null $deleted_by
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $deleted_time
 * @property-read string $time
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUserId($value)
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $uuid
 * @property string $user_id
 * @property string $wallet_id
 * @property string $amount
 * @property string $transaction_type
 * @property string $status
 * @property string|null $exchange_rate
 * @property string|null $deposit_address
 * @property string|null $deposited_from
 * @property int|null $game_id
 * @property string|null $transfer_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Gamelist|null $game
 * @property-read \App\Models\User|null $transferTo
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Wallet $wallet
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDepositAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDepositedFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereExchangeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTransactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTransferTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereWalletId($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $uuid
 * @property string|null $username
 * @property string|null $avatar
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wallet> $wallets
 * @property-read int|null $wallets_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUuid($value)
 */
	class User extends \Eloquent implements \Tymon\JWTAuth\Contracts\JWTSubject, \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $uuid
 * @property string $user_id
 * @property \App\Models\Cryptocurrency|null $currency
 * @property string|null $deposit_address
 * @property string $balance
 * @property bool $is_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereDepositAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUuid($value)
 */
	class Wallet extends \Eloquent {}
}

