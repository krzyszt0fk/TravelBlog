<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Statystyki strony') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('W tym miejscu wyświetlają sie statystyki strony') }}
        </p>
    </header>
                    <div class="mt-6" x-data="{ tab: 1 }">
                        <div class="flex mx-2 mb-4 space-x-4 text-xl border-b border-gray-300">
                            <div class="hover:text-indigo-600 py-2 cursor-pointer"
                                 :class="{ 'text-indigo-600 border-b border-indigo-600': tab == 1 }"
                                 @click="tab = 1">
                                Użytkownicy</div>

                            <div class="hover:text-indigo-600 py-2 pl-2 cursor-pointer"
                                 :class="{ 'text-indigo-600 border-b border-indigo-600': tab == 2 }"
                                 @click="tab = 2">
                                Posty</div>


                        </div>
                        <div x-show="tab === 1"> <b>Ilość kont na stronie: </b> {{ $totalUsers }}
                            <ul>

                            </ul>
                        </div>
                        <div x-show="tab === 1"><b>Ilość użytkowników którzy wstawili post na strone:</b>{{ $usersWithPostsCount }}
                            <ul>

                            </ul>
                        </div>
                        <div x-show="tab === 1"><b>Konto/Konta z największą liczbą obserwujących:</b>
                            <ul>
                                @if ($usersWithMaxFollowers->count() > 0)
                                    @foreach ($usersWithMaxFollowers as $user)
                                        <li>
                                            {{ $user->name }} – {{ $user->followers_count }} followers
                                        </li>
                                    @endforeach
                                @else
                                    <li>Brak danych</li>
                                @endif
                            </ul>
                        </div>
                        <div x-show="tab === 1"><b>Konto/Konta z największą liczbą obserwowanych użytkowników:</b>
                            <ul>
                                @if ($usersWithMaxFollowing->count() > 0)
                                    @foreach ($usersWithMaxFollowing as $user)
                                        <li>
                                            {{ $user->name }} – {{ $user->following_count }} obserwowanych
                                        </li>
                                    @endforeach
                                @else
                                    <li>Brak danych</li>
                                @endif
                            </ul>
                        </div>

                        <div x-show="tab === 2"><b>Ilość postów na stronie: </b>{{ $totalPosts }}
                            <ul class="space-y-4">

                            </ul>
                        </div>
                        <div x-show="tab === 2"><b>Posty z największą liczbą polubień: </b>
                            <ul>
                                @if ($postsWithMaxLikes->count() > 0)
                                    @foreach ($postsWithMaxLikes as $post)
                                        <li>
                                            "{{ $post->title }}" – {{ $post->users_that_like_count }} polubień
                                        </li>
                                    @endforeach
                                @else
                                    <li>Brak danych</li>
                                @endif
                            </ul>
                        </div>
                        <div x-show="tab === 2"><b>Posty z największą liczbą nie polubień: </b>
                            <ul>
                                @if ($postsWithMaxDislikes->count() > 0)
                                    @foreach ($postsWithMaxDislikes as $post)
                                        <li>
                                            "{{ $post->title }}" – {{ $post->users_that_dislike_count }} niepolubień
                                        </li>
                                    @endforeach
                                @else
                                    <li>Brak danych</li>
                                @endif
                            </ul>
                        </div>


                    </div>




</section>
