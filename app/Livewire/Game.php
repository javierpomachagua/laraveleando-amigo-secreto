<?php

namespace App\Livewire;

use App\Models\Player;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Livewire\Component;

class Game extends Component
{
    public $players;

    public string $gameUuid;

    public $gameStarted = false;

    public function mount(): void
    {
        $this->initGame();

        if (Cookie::has('game_uuid')) {
            $this->gameUuid = Cookie::get('game_uuid');
            $this->gameStarted = true;
            $this->players = Player::where('game_uuid', $this->gameUuid)->get();
        }
    }

    public function render()
    {
        return view('livewire.game');
    }

    public function addPlayer(): void
    {
        $this->players->push([
            'name' => '',
        ]);
    }

    public function removePlayer($key): void
    {
        $this->players->forget($key);
    }

    public function startGame()
    {
        $validated = $this->validate([
            'players.*.name' => 'required|string|max:100',
        ], messages: [
            'players.*.name.required' => 'El campo :attribute es obligatorio.',
            'players.*.name.string' => 'El campo :attribute debe ser una cadena de texto.',
            'players.*.name.max' => 'El campo :attribute no puede tener más de :max caracteres.',
        ], attributes: [
            'players.*.name' => 'nombre del jugador',
        ]);

        $players = collect($validated['players'])->map(function ($player) {
            return Player::create([
                'name' => $player['name'],
                'game_uuid' => $this->gameUuid,
                'uuid' => Str::uuid(),
            ]);
        });

        $shuffledPlayers = $players->shuffle();

        $shuffledPlayers->each(fn (Player $player, $index) => $player->update([
            'secret_friend_id' => $shuffledPlayers->get($index + 1)->id ?? $shuffledPlayers->first()->id,
        ]));

        $this->gameStarted = true;

        $this->players = $shuffledPlayers;

        Cookie::queue('game_uuid', $this->gameUuid, 60 * 24);
    }

    public function restartGame(): void
    {
        $this->initGame();

        Cookie::forget('game_uuid');
        Cookie::expire('game_uuid');
    }

    private function initGame(): void
    {
        $this->players = Collection::times(5, fn () => [
            'name' => '',
        ]);

        $this->gameUuid = Str::uuid();

        $this->gameStarted = false;
    }
}
