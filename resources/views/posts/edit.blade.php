<!--
************ FORMULARZ HTML DO EDYTOWANIA POSTA ************************
-->

<x-ProjektPAI.layout>

    <x-slot:title>
        Edytuj post
    </x-slot:title>

    <div class="my-14">
        <h1 class="text-4xl">Edytuj post</h1>

        <div class="w-full">

            <!-- GŁÓWNY FORMULARZ: EDYCJA POSTA (tytuł, treść) + DODAWANIE ZDJĘĆ -->
            <form method="POST" action="{{ route('posts.update', $post) }}"
                  class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"
                  enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <!-- Tytuł -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2" for="post-title">
                        Tytuł
                    </label>
                    <input name="title" required
                           class="shadow appearance-none border {{ $errors->first('title') ? 'border-red-600' : '' }}
                                  rounded w-full py-2 px-3 text-gray-700 mb-2 leading-tight focus:outline-none
                                  focus:shadow-outline"
                           id="post-title"
                           type="text"
                           value="{{ old('title', $post->title) }}">
                    <p class="text-red-500 text-xs italic">{{ $errors->first('title') }}</p>
                </div>

                <!-- Treść -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2" for="post-content">
                        Treść
                    </label>
                    <textarea name="content" required
                              id="post-content"
                              class="drop-shadow-lg w-full h-60 p-4 border
                                     {{ $errors->first('content') ? 'border-red-600' : '' }}
                                     focus:outline-none focus:shadow-outline">{{ old('content', $post->content) }}</textarea>
                    <p class="text-red-500 text-xs italic">{{ $errors->first('content') }}</p>
                </div>

                <!-- Dodawanie nowych zdjęć (jeśli jest jeszcze miejsce) -->
                @php
                    $remainingSlots = 3 - $post->images->count();
                @endphp
                @if($remainingSlots > 0)
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2" for="post-images">
                            Dodaj zdjęcia (możesz dodać maks. {{ $remainingSlots }}):
                        </label>
                        <input type="file"
                               name="images[]"
                               id="post-images"
                               class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100"
                               multiple
                               accept="image/*">
                        @error('images.*')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <!-- Przycisk zapisu (edycja posta + dodawanie zdjęć) -->
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer"
                        type="submit">
                    Zapisz
                </button>
            </form>

            <!-- OSOBNA SEKCJA: LISTA ISTNIEJĄCYCH ZDJĘĆ + USUWANIE KAŻDEGO Z OSOBNA -->
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 mt-6">
                <h2 class="block text-gray-700 font-bold mb-2">
                    Aktualne zdjęcia ({{ $post->images->count() }}/3)
                </h2>

                @forelse($post->images as $image)
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('storage/' . $image->path) }}"
                             alt="Zdjęcie"
                             class="w-20 h-20 object-cover mr-4 rounded border">

                        <!-- Formularz do usunięcia TYLKO jednego zdjęcia -->
                        <form method="POST" action="{{ route('posts.update', $post) }}">
                            @csrf
                            @method('PATCH')
                            <!-- Pole hidden -> ID usuwanego zdjęcia -->
                            <input type="hidden" name="remove_image_id" value="{{ $image->id }}">
                            <button type="submit"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">
                                Usuń
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-500">Brak zdjęć w tym poście.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-ProjektPAI.layout>

