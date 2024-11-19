<div class="bg-gradient-to-t from-red-500 to-green-500 min-h-screen flex items-center justify-center py-20">
    <div class="bg-white lg:w-5/12 md:w-6/12 w-10/12 shadow-3xl">
        <div class="bg-gray-800 absolute left-1/2 transform -translate-x-1/2 -translate-y-1/2 rounded-full p-4 md:p-8">
            <svg class="stroke-white w-12 h-12" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
            </svg>
        </div>
        @if(! $gameStarted)
            <form class="p-12 pt-20">
                @foreach($players as $key => $player)
                    <div wire:key="{{ $key }}" class="mb-5">
                        <div class="flex items-center gap-4 text-lg">
                            <svg class="absolute ml-3" width="24" viewBox="0 0 24 24">
                                <path
                                    d="M20.822 18.096c-3.439-.794-6.64-1.49-5.09-4.418 4.72-8.912 1.251-13.678-3.732-13.678-5.082 0-8.464 4.949-3.732 13.678 1.597 2.945-1.725 3.641-5.09 4.418-3.073.71-3.188 2.236-3.178 4.904l.004 1h23.99l.004-.969c.012-2.688-.092-4.222-3.176-4.935z"/>
                            </svg>
                            <input type="text" placeholder="Nombre del jugador" wire:model="players.{{ $key }}.name"
                                   class="bg-gray-200 pl-12 py-2 md:py-4 focus:outline-none w-full">
                            <button type="button" wire:click="removePlayer({{ $key }})">X</button>
                        </div>
                        @error('players.'.$key.'.name')
                        <p class="text-red-400 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
                <button type="button"
                        class="bg-gradient-to-b from-gray-300 to-gray-400 font-medium p-2 md:p-4 text-gray-900 uppercase w-full hover:from-gray-400 hover:to-gray-500"
                        wire:click="addPlayer">
                    Añadir Jugador
                </button>
                <button type="button"
                        class="bg-gradient-to-b from-gray-700 to-gray-800 font-medium p-2 md:p-4 text-white uppercase w-full mt-2 hover:from-gray-800 hover:to-gray-900"
                        wire:click="startGame">
                    Empezar Juego
                </button>
            </form>
        @else
            <div class="p-12 text-left pt-20">
                @foreach($players as $player)
                    <p class="mb-2">
                        <span
                            class="text-blue-500 underline cursor-pointer"
                            @click="copyToClipboard('{{ route('secret-friend.show', ['game_uuid' => $gameUuid, 'player' => $player->uuid]) }}')">Copiar enlace</span>
                        <span>de {{ $player->name }}</span>
                    </p>
                @endforeach
            </div>
        @endif
        <button type="button"
                class="bg-white font-medium p-2 md:p-4 border border-gray-500 text-gray-900 uppercase w-full mt-2 hover:bg-gray-100"
                wire:click="restartGame">
            Reiniciar Juego
        </button>
    </div>
</div>

<script>
    function copyToClipboard(url) {
        let tempTextarea = document.createElement('textarea');
        tempTextarea.value = url;
        tempTextarea.style.position = 'absolute';
        tempTextarea.style.left = '-9999px'
        document.body.appendChild(tempTextarea)
        tempTextarea.select()
        document.execCommand('copy')
        alert('¡Enlace copiado!');
        document.body.removeChild(tempTextarea)
    }
</script>
