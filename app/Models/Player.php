<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function secretFriend(): HasOne
    {
        return $this->hasOne(Player::class, 'secret_friend_id', 'id');
    }
}
