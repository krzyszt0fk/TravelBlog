<!--
**************** WIDOK DO POKAZANIA KONKRETNEGO POSTA ************************
-->

<x-projektPAI.layout>

        <x-slot:title>
            Szczegóły posta
        </x-slot:title>
        <div class="my-14 flex flex-col">
            <div class="text-center">
                <p class="text-gray-500">{{ $post->created_at->format('j M Y') }}</p>
                <p class="italic text-sm">by {{ $post->user->name }} <img class="ml-2 object-scale-down h-14 w-14 rounded-full inline"
                                                                          src="{{ asset('storage/' . $post->user->image?->path) }}" alt="profile image"></p>

                <h1 class="mb-10 text-6xl font-bold tracking-tighter mt-5">{{ $post->title }}</h1>
                <hr>
            </div>

            <p class="text-gray-500 mt-10 leading-8">
                {{ $post->content }}
            </p>

            @if($post->images->count() > 0)
                <div class="mt-10" x-data="{ currentSlide: 0 }">
                    <!-- Slajdy -->
                    <div class="relative w-full h-96 overflow-hidden">
                        <template x-for="(image, index) in {{ $post->images->toJson() }}" :key="index">
                            <div x-show="currentSlide === index"
                                 class="absolute inset-0 flex items-center justify-center">
                                <img :src="'/storage/' + image.path"
                                     alt="zdjęcie posta"
                                     class="max-h-96 object-contain rounded shadow-lg">
                            </div>
                        </template>
                    </div>
                    <!-- Przyciski prev / next -->
                    <div class="flex justify-center mt-4 space-x-4">
                        <button class="bg-blue-300 px-4 py-2 rounded hover:bg-blue-400 "
                                @click="currentSlide = (currentSlide === 0) ? {{ $post->images->count() - 1 }} : currentSlide - 1 ">
                            Poprzednie
                        </button>
                        <button class="bg-blue-300 px-4 py-2 rounded hover:bg-blue-400"
                                @click="currentSlide = (currentSlide === {{ $post->images->count() - 1 }}) ? 0 : currentSlide + 1">
                            Następne
                        </button>
                    </div>
                </div>
            @else
                <p class="text-gray-500 mt-10 leading-8">
                    Brak zdjęć w tym poście.
                </p>
            @endif

            <div class="flex mt-10">
                <!-- Link do edycji tylko dla wlasciciela posta -->
                @if ($post->user->is(auth()->user())||(auth()->check() && auth()->user()->is_admin))
                    <div class="flex">
                        <a href="{{ route('posts.edit', $post->id) }}" title="edit" class="mr-2 cursor-pointer">
                            <x-projektPAI.images.edit-icon /> <!-- wczytanie komponentu - ikonka edycji -->
                        </a>

                        <form method="POST" action="{{ route('posts.destroy', $post) }}">
                            @csrf
                            @method('delete')
                            <button type="submit" href="{{ route('posts.destroy', $post) }}"
                                    onclick="return confirm('Czy na pewno chcesz usunąć ten post?')" title="delete" class="cursor-pointer">
                                <x-projektPAI.images.delete-icon /> <!-- wczytanie komponentu - ikonka usunięcia posta -->
                            </button>
                        </form>

                    </div>
                @endif

            </div>
            <div class="flex mt-10">


                <livewire:like :post="$post" />
                @if (Auth::user() && Auth::user()->id != $post->user->id)
                    @if (Auth::user()->isFollowing($post->user))
                        <div class="ml-4">
                        Obserwujesz:&nbsp;<a class="text-green-500 hover:text-green-700" href="{{ route('posts.user', $post->user->id) }}">
                            {{ $post->user->name }}</a>
                    @else
                        <a href="{{ route('toggleFollow', $post->user) }}"
                           class="ml-3 inline font-bold text-sm px-6 py-2 text-white rounded bg-blue-500 hover:bg-blue-600">
                            {{ __('Obserwuj autora posta') }}</a>
                    @endif
                        </div>
                @endif
            </div>

        </div>

</x-projektPAI.layout>
