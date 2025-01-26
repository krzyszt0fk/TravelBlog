<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- jesli będzie ustawiony tytul strony to zostanie wstawiony, jesli nie to wtedy null-->
    <title>{{ $title ?? null }}</title>
    <link rel="icon" href="{{ asset('logo-icon.svg') }}" type="image/svg+xml">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">

<!-- powiadomienie u gory o nowym wpisie
x-data - tu znajduja sie poczatkowe dane dla tego diva,
x-show - sterowanie wyswietlaniem tego diva
x-init - funkcja ktora sie wykona po pierwszym wczytaniu elementu
@close.window - obsluga zdarzenia klikniecia 'x'
x-html  - zawartosc spana-->
<!--
<div x-data="{ show: true, message: 'New blog posts has been written' }"
     x-show="show" x-init="setTimeout(() => show = false, 5000)"
     @close.window="show=false" class="flex justify-between m-auto w-3/4 text-blue-200 shadow-inner p-3 bg-blue-600 transition-opacity duration-500 ease-in-out"
     x-transition:enter="opacity-100" x-transition:leave="opacity-0">
    <p><strong>Info </strong><span x-html="message"></span></p>
    <strong @click="$dispatch('close')" class="text-xl align-center cursor-pointer">&times;</strong>
</div>
-->


<!-- kontener dla calej strony -->
<div class="container mx-auto p-8">

    <!-- pasek u gory -->
    <header class = "flex justify-between items-center shadow-md rounded-2xl  border-2 ">

        <!-- logo i wyszukiwarka -->
        <div class="flex items-center">
            <!-- logo strony -->
            <a href="{{url('/')}}"><x-projektPAI.images.logo-image /> </a><!-- wczytanie komponentu - logo -->

            <div class="text-2xl hidden md:block text-gray-700 font-medium ml-2 tracking-tight">
                <a href="{{url('/')}}">TravelBlog</a>   <!-- Klikniecie w nazwe strony przekierowuje na strone glowna -->
            </div>


        </div>

        <!-- linki - wyswietlane w zaleznosci czy uzytkownik zalogowany, za pomoca if else-->
        <div class="text-lg hidden md:flex space-x-8 mr-5 ">

            @if (Auth::check())
                <p><a class="text-gray-700 " >Zalogowano jako: </a><a class="hover:text-stone-500" href="{{ route('dashboard') }}">{{Auth::user()->name}}</a></p>
            <!-- formularz do wylogowania -->
            <x-projektPAI.logout-form/>  <!-- wstawienie komponentu do wylogowania -->
            <a href="{{ route('posts.create') }}"
               class="inline font-bold text-sm px-6 py-2 text-white rounded-full bg-red-500 hover:bg-red-600">{{__('Nowy post')}}</a> <!-- Aby tekst mogl byc przetlumaczony w zaleznosci od lokazlizacji, uzywamy takiego zapisu -->
            @else
                <a class="tracking-widest hover:text-stone-500" href="{{ route('login') }}">Zaloguj</a>
            <a class="tracking-widest hover:text-stone-500" href="{{ route('register') }}">Rejestracja</a>
            @endif
        </div>
        <!-- hamburger icon -->
        <div id="hamburger-icon" class="space-y-2 cursor-pointer md:hidden mr-3">
            <div class="w-8 h-0.5 bg-gray-700"></div>
            <div class="w-8 h-0.5 bg-gray-700"></div>
            <div class="w-8 h-0.5 bg-gray-700"></div>
        </div>
    </header>

    <!-- menu dla urzadzen mobilnych -->
    <div class="md:hidden">
        <div id="mobile-menu"
             class="flex-col items-center hidden py-8 mt-10 space-y-6 bg-white left-6 right-6 drop-shadow-lg">
            @if (Auth::check())
            <p>Zalogowano jako: <a class="hover:text-stone-500" href="{{ route('dashboard') }}">{{Auth::user()->name}}</a></p>

            <x-projektPAI.logout-form/>  <!-- wstawienie komponentu do wylogowania -->

            <a href="{{ route('posts.create') }}"
               class="inline font-bold text-sm px-6 py-2 text-white rounded-full bg-red-500 hover:bg-red-600">
                Nowy post</a>
            @else
            <a class="tracking-widest hover:text-stone-500" href="{{ route('login') }}">Logowanie</a>
            <a class="tracking-widest hover:text-stone-500" href="{{ route('register') }}">Rejestracja</a>
            @endif
        </div>
    </div>



    {{$slot}}

    <!-- stopka -->
    <footer class="flex items-center justify-center mt-10">
        &copy; 2025 TravelBlog
    </footer>
</div>
@livewireScripts
</body>
</html>
