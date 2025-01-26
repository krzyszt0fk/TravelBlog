<!--
************** LISTA WSZYTKICH POSTOW LUB OD KONKRETNEGO UŻYTKOWNIKA *****************
-->
<x-ProjektPAI.layout>
    <!-- Dunamiczny tytuł dla kazdej strony-->
    <x-slot:title>
        Wszystkie posty
    </x-slot:title>
        <!-- ostatnie wpisy - informacje -->
        <div class="my-14 ">
            <h1 class="text-6xl tracking-tighter font-bold mb-6">Ostatnie wpisy {{$user ?? null}}</h1>
            <div class="mb-8 text-gray-500 flex items-center justify-between">
                <p>Blog o tematyce podróżniczej. Podziel się swoimi doświadczeniami z podróży!</p>

                <!-- Form do wyboru sortowania -->
                <form method="GET" action="{{ route('posts.index') }}">
                    <label for="sort">Sortuj:</label>
                    <select name="sort" id="sort" class="border rounded ml-2" onchange="this.form.submit()">
                        <option value="date_desc"
                            {{ request('sort') === 'date_desc' ? 'selected' : '' }}>
                            Od najnowszych
                        </option>
                        <option value="date_asc"
                            {{ request('sort') === 'date_asc' ? 'selected' : '' }}>
                            Od najstarszych
                        </option>
                        <option value="likes_desc"
                            {{ request('sort') === 'likes_desc' ? 'selected' : '' }}>
                            Najbardziej lubiane
                        </option>
                    </select>
                </form>
            </div>

            <hr />
        </div>
        @foreach($posts as $post)
        <!-- wyświetlanie ostatnich wpisów -->
        <div class="my-14 flex flex-col md:flex-row">
            <p class="mb-8 text-gray-500 mr-20">{{$post->created_at->format('j M Y')}}</p>
            <div class="space-y-4">
                <h1 class="text-3xl font-bold tracking-tighter">{{$post->title}}</h1>
                <p class="text-gray-500">{{Str::limit($post->content, 200,'...')}}</p><!-- limit wyświetlania tresci posta 200 znakow -->

                <!-- Wyświetlenie miniatur zdjęć posta -->
                @if($post->images->count() > 0)
                    <div class="flex space-x-2">
                        @foreach($post->images as $image)
                            <img src="{{ asset('storage/' . $image->path) }}"
                                 alt="zdjęcie posta"
                                 class="w-20 h-20 object-cover border rounded" />
                        @endforeach
                    </div>
                @endif

                <p><a class="text-red-500 hover:text-red-900 mt-8" href="{{ route('posts.show', $post) }}">Czytaj więcej</a>
                </p>
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
        </div>
        <hr />
        @endforeach
    <br>
    {{$posts->links()}}

    </x-ProjektPAI.layout>
