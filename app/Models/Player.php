<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'saw_secret_friend' => 'boolean',
        ];
    }

    public function secretFriend(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'secret_friend_id', 'id');
    }
}
