<?php

use App\Models\Player;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

it('updates to true the column saw secret friend', function () {
    $gameUuid = Str::uuid();

    $players = Player::factory()
        ->state([
            'game_uuid' => $gameUuid,
            'saw_secret_friend' => false,
        ])
        ->count(5)
        ->create();

    $shuffledPlayers = $players->shuffle();

    $shuffledPlayers->each(fn (Player $player, $index) => $player->update([
        'secret_friend_id' => $shuffledPlayers->get($index + 1)->id ?? $shuffledPlayers->first()->id,
    ]));

    foreach ($shuffledPlayers as $player) {
        expect($player->saw_secret_friend)->toBeFalse();
    }

    get(route('secret-friend.show', ['game_uuid' => $gameUuid, 'player' => $shuffledPlayers->first()->uuid]));

    expect($shuffledPlayers->first()->refresh()->saw_secret_friend)->toBeTrue();
});

it('returns the secret friend name', function () {
    $gameUuid = Str::uuid();

    $players = Player::factory()
        ->state([
            'game_uuid' => $gameUuid,
            'saw_secret_friend' => false,
        ])
        ->count(5)
        ->create();

    $shuffledPlayers = $players->shuffle();

    $shuffledPlayers->each(fn (Player $player, $index) => $player->update([
        'secret_friend_id' => $shuffledPlayers->get($index + 1)->id ?? $shuffledPlayers->first()->id,
    ]));

    get(route('secret-friend.show', ['game_uuid' => $gameUuid, 'player' => $shuffledPlayers->first()->uuid]))
        ->assertSee('Tu amigo secreto es '.$shuffledPlayers->first()->refresh()->secretFriend->name);

});

it('returns an invalid game uuid text', function () {
    $gameUuid = Str::uuid();

    $players = Player::factory()
        ->state([
            'game_uuid' => $gameUuid,
            'saw_secret_friend' => false,
        ])
        ->count(5)
        ->create();

    $shuffledPlayers = $players->shuffle();

    $shuffledPlayers->each(fn (Player $player, $index) => $player->update([
        'secret_friend_id' => $shuffledPlayers->get($index + 1)->id ?? $shuffledPlayers->first()->id,
    ]));

    get(route('secret-friend.show', ['game_uuid' => fake()->uuid, 'player' => $shuffledPlayers->first()->uuid]))
        ->assertSee('No perteneces a este juego');
});

it('returns a text if the player already has seen the secret friend', function () {
    $gameUuid = Str::uuid();

    $players = Player::factory()
        ->state([
            'game_uuid' => $gameUuid,
            'saw_secret_friend' => false,
        ])
        ->count(5)
        ->create();

    $shuffledPlayers = $players->shuffle();

    $shuffledPlayers->each(fn (Player $player, $index) => $player->update([
        'secret_friend_id' => $shuffledPlayers->get($index + 1)->id ?? $shuffledPlayers->first()->id,
    ]));

    get(route('secret-friend.show', ['game_uuid' => $gameUuid, 'player' => $shuffledPlayers->first()->uuid]));

    get(route('secret-friend.show', ['game_uuid' => $gameUuid, 'player' => $shuffledPlayers->first()->uuid]))
        ->assertSee('Ya has visto a tu amigo secreto');
});
