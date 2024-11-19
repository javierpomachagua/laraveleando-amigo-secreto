<?php

use App\Livewire\Game;
use App\Models\Player;
use Illuminate\Support\Collection;
use Livewire\Livewire;

it('inits the game properties', function () {
    Livewire::test(Game::class)
        ->assertSet('players', Collection::times(5, fn () => [
            'name' => '',
        ]))
        ->assertSet('gameUuid', fn ($value) => ! empty($value))
        ->assertSet('gameStarted', false);
});

it('renders the html successfully', function () {
    Livewire::test(Game::class)
        ->assertSee(['Añadir Jugador', 'Empezar Juego', 'Reiniciar Juego']);
});

it('adds a player', function () {
    Livewire::test(Game::class)
        ->call('addPlayer')
        ->assertSet('players', fn ($value) => count($value) === 6);
});

it('removes a player', function () {
    Livewire::test(Game::class)
        ->call('removePlayer', 1)
        ->assertSet('players', fn ($value) => count($value) === 4);
});

it('starts a game', function () {
    Livewire::test(Game::class)
        ->set('players', collect([
            [
                'name' => 'Ana',
            ],
            [
                'name' => 'Mario',
            ],
            [
                'name' => 'Fiona',
            ],
            [
                'name' => 'Daniel',
            ],
            [
                'name' => 'Roberto',
            ],
        ]))
        ->call('startGame')
        ->assertSet('gameStarted', true);

    $players = Player::all();

    expect($players->count())->toBe(5);

    foreach ($players as $player) {
        expect($player->secretFriend()->exists())->toBeTrue();
    }

});

it('restarts a game', function () {
    Livewire::test(Game::class)
        ->set('players', collect([
            [
                'name' => 'Ana',
            ],
            [
                'name' => 'Mario',
            ],
            [
                'name' => 'Fiona',
            ],
            [
                'name' => 'Daniel',
            ],
            [
                'name' => 'Roberto',
            ],
            [
                'name' => 'Raúl',
            ],
        ]))
        ->call('startGame')
        ->call('restartGame')
        ->assertSet('players', fn ($value) => count($value) === 5)
        ->assertSet('gameStarted', false);
});
