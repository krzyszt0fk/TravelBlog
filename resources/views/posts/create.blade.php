<!--
*********** Wyświetlenie formularza dodania nowego posta *************************
-->

<x-ProjektPAI.layout>

    <!-- Dunamiczny tytuł dla kazdej strony-->
    <x-slot:title>
        Nowy post
    </x-slot:title>

    <div class="my-14">
        <h1 class="text-4xl">Stwórz nowy post!</h1>
        <div class="w-full">
            <form method="POST" action="{{ route('posts.store') }}"
                  class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"
                  enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2" for="post-title">
                        Tytuł
                    </label>
                    <!-- Jezeli bedzie blad w polu title to wyswietli klase border-red-600, w przeciwnym wypadku null -->
                    <input required name="title"
                           class="shadow appearance-none border {{$errors->first('title') ? 'border-red-600':null}} rounded w-full py-2 px-3 text-gray-700 mb-2 leading-tight focus:outline-none focus:shadow-outline"
                           id="post-title" type="text" placeholder="napisz tytuł" value="{{old('title')}}">
                    <p class="text-red-500 text-xs italic">{{$errors->first('title')}}</p>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2" for="post-content">
                        Treść posta
                    </label>
                    <textarea name="content" required id="post-content"
                              class="drop-shadow-lg w-full h-60 p-4 border {{$errors->first('content') ? 'border-red-600':null}} focus:outline-none focus:shadow-outline"
                              placeholder="napisz treść posta">{{old('content')}}</textarea>
                    <p class="text-red-500 text-xs italic">{{$errors->first('content')}}</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2" for="post-images">
                        Zdjęcia (maks. 3)
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


                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer"
                        type="submit">
                    Zapisz
                </button>
            </form>
        </div>
    </div>


</x-ProjektPAI.layout>
