<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Jesteś zalogowany!") }}
                    <div class="mt-6" x-data="{ tab: 1 }">
                        <div class="flex mx-2 mb-4 space-x-4 text-xl border-b border-gray-300">
                            <div class="hover:text-indigo-600 py-2 cursor-pointer"
                                 :class="{ 'text-indigo-600 border-b border-indigo-600': tab == 1 }"
                                 @click="tab = 1">
                                Obserwujący</div>

                            <div class="hover:text-indigo-600 py-2 pl-2 cursor-pointer"
                                 :class="{ 'text-indigo-600 border-b border-indigo-600': tab == 2 }"
                                 @click="tab = 2">
                                Obserwowani</div>

                            <div class="hover:text-indigo-600 py-2 pl-2 cursor-pointer"
                                 :class="{ 'text-indigo-600 border-b border-indigo-600': tab == 3 }"
                                 @click="tab = 3">
                                Polubione posty</div>
                        </div>
                        <div x-show="tab === 1"><b>Osoby, które Cię obserwują:</b>
                            <ul>
                                @foreach(Auth::user()->followers()->get() as $follower)
                                <li><a class="hover:text-stone-500" href="{{ route('posts.user',$follower->id) }}">{{$follower->name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div x-show="tab === 2"><b>Osoby, które obserwujesz:</b>
                            <ul class="space-y-4">
                                @foreach(Auth::user()->following()->get() as $following)
                                <li><a class="hover:text-stone-500" href="{{ route('posts.user', $following->id) }}">{{$following->name}}</a><a
                                        href="{{ route('toggleFollow', $following) }}"
                                        class="ml-3 inline font-bold text-sm px-4 py-1 text-white rounded bg-blue-500 hover:bg-blue-600">
                                        Unfollow</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div x-show="tab === 3"><b>Posty które lubisz:</b>
                            <ul>
                                @foreach (Auth::user()->likedPosts()->get() as $lpost)
                                    <li><a class="hover:text-stone-500" href="{{ route('posts.show', $lpost->id) }}">{{ $lpost->title }}
                                            </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @if(auth()->check() && auth()->user()->is_admin)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">

                    @include('profile.partials.admin-info')

                </div>
            </div>

        </div>
    @endif

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                <h3 class="text-lg font-bold mb-4">Twoje posty</h3>
                <ul class="space-y-2">
                    @foreach (Auth::user()->posts as $post)
                        <li>
                            <a href="{{ route('posts.show', $post->id) }}" class="text-blue-600 hover:underline">
                                {{ $post->title }}
                            </a>
                            <span class="text-gray-500 text-sm">({{ $post->created_at->format('d-m-Y') }})</span>
                        </li>
                    @endforeach
                </ul>
                @if(Auth::user()->posts->isEmpty())
                    <p class="text-gray-500">Nie masz jeszcze żadnych postów.</p>
                @endif

            </div>
        </div>

    </div>
</x-app-layout>
