<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Zdjęcie profilowe
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Ustaw swoje zdjęcie profilowe
        </p>
    </header>

    <img src="{{ asset('storage/' . $user->image?->path) }}" alt="profile image">

    <form action="{{ route('profile.image') }}" method="POST" class="p-4" enctype="multipart/form-data">
        @csrf
        <label class="block mb-4">
            <span class="sr-only">Choose File</span>
            <input type="file" name="image"
                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
            @error('image')
            <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </label>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Zapisz') }}</x-primary-button>
            @if (session('status') === 'image-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">
                    Saved</p>
            @endif
        </div>


    </form>
</section>
