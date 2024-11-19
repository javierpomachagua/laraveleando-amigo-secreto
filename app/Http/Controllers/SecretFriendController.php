<?php

namespace App\Http\Controllers;

use App\Models\Player;

class SecretFriendController extends Controller
{
    public function show(string $gameUuid, Player $player)
    {
        if ($player->game_uuid !== $gameUuid) {
            return view('secret-friend-show', [
                'text' => 'No perteneces a este juego',
            ]);
        }

        if ($player->saw_secret_friend) {
            return view('secret-friend-show', [
                'text' => 'Ya has visto a tu amigo secreto',
            ]);
        }

        $player->update([
            'saw_secret_friend' => true,
        ]);

        return view('secret-friend-show', [
            'text' => 'Tu amigo secreto es '.$player->secretFriend->name,
        ]);
    }
}
