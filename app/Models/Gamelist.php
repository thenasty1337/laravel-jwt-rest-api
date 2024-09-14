<?php

namespace App\Models;

use App\Enums\StreamSessionsStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

/**
 * Class Bet
 * @mixin Builder
 *
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
 *
 */
class Gamelist extends Model
{
    use HasTags;

    protected $table = 'gamelist';
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'type',
        'id_hash',
        'category',
        'new',
        'image',
        'freerounds_supported',
        'index_rating',
        'image_portrait',
        'active',
    ];

    protected $casts = [
        'freerounds_supported' => 'boolean',
        'play_for_fun_supported' => 'boolean',
        'popular' => 'boolean',
        'index_rating' => 'integer',
        'active' => 'boolean',
        'new' => 'boolean',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'game_id');
    }

}
