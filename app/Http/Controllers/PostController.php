<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Models\Post;
use App\Models\User;
use App\Models\PostImage;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //do wyswietlania wszystkich postow
        public function index(Request $request)
    {
        // Odczytujemy parametry
        $sort = $request->input('sort', 'date_desc'); // domyślnie date_desc

        // Budujemy zapytanie bazowe
        $query = Post::withCount('usersThatLike')->with('user');

        // Na podstawie parametru 'sort' ustawiamy orderBy
        switch ($sort) {
            case 'date_asc':
                // od najstarszych
                $query->orderBy('created_at', 'asc');
                break;

            case 'likes_desc':
                // najbardziej lubiane
                $query->orderBy('users_that_like_count', 'desc');
                break;

            case 'date_desc':
            default:
                // od najnowszych
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Wykonanie zapytania i stronicowanie
        $posts = $query->paginate(4);

        // Przekazujemy zmienną $posts do widoku
        return view('posts.index', [
            'posts' => $posts
        ]);
    }
/*
        //eager loading - zaladowanie dodatkowego modelu do rezultatu z bazy danych, ładują sie posty razem z uzytkownikami
        return view('posts.index',  [
            'posts' => Post::withCount('usersThatLike')->with('user')
                ->orderByDesc('users_that_like_count')->paginate(4)]);//ilosc wyswietlanych postow na stronie
    }
*/
    /**
     * Show the form for creating a new resource.
     */
    //do wyswietlania formularza do stworzenia nowego posta
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    //do zapisania posta w bazie danych
    public function store(Request $request)
    {
        //walidacja podstawowych pól posta
        $validatedData = $request->validate([
            'title'   => 'required|max:30|min:3|string',
            'content' => 'required|min:10|string|max:1500',
            // 2. Walidacja zdjęć
            'images.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            // * oznacza każdy element tablicy images[]
        ]);

        // limit 3 zdjęć
        if ($request->hasFile('images')) {
            if (count($request->file('images')) > 3) {
                return back()->withErrors([
                    'images.*' => 'Możesz przesłać maksymalnie 3 zdjęcia',
                ]);
            }
        }

        // nowy post (powiązany z userem)
        $post = $request->user()->posts()->create($validatedData);

        //Zapis plików (o ile przesłano)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $uploadedFile) {
                // Zapisz plik w storage/app/public/images
                $imagePath = $uploadedFile->store('images', 'public');

                // Utwórz rekord w bazie (z post_id)
                $post->images()->create([
                    'path' => $imagePath,
                ]);
            }
        }

        //przekierowanie
        return redirect()->route('posts.index');
    }


    /**
     * Display the specified resource.
     */
    //do pokazania pojedynczego posta
    public function show(Post $post,$locale='en')
    {
        App::setLocale($locale);
        return view('posts.show',['post'=>$post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    //do wyswietlania formularza do edytowania posta
    public function edit(Post  $post)
    {

        return view('posts.edit',['post'=>$post]);
    }

    /**
     * Update the specified resource in storage.
     */
    //do zapisywania edytowanego posta
    public function update(Request $request, Post $post)
    {
        // 1. Czy chcemy usunąć konkretne zdjęcie?
        if ($request->has('remove_image_id')) {
            $imageId = $request->input('remove_image_id');
            $image   = PostImage::findOrFail($imageId);

            // opcjonalnie sprawdź, czy zdjęcie należy do posta
            if ($image->post_id !== $post->id) {
                abort(403, 'Brak dostępu do tego zdjęcia.');
            }

            // usuwamy fizyczny plik ze storage
            Storage::disk('public')->delete($image->path);
            // usuwamy rekord z bazy
            $image->delete();

            // przekierowanie z komunikatem
            return back()->with('status', 'Zdjęcie usunięte.');
        }

        // 2. Zwykła edycja: tytuł, treść + ewentualne dodanie nowych zdjęć
        $validated = $request->validate([
            'title' => 'required|string|max:20',
            'content' => 'required|string|max:1000',
            'images.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048', // walidacja nowych zdjęć
        ]);

        // Aktualizacja pola title, content
        $post->update([
            'title'   => $validated['title'],
            'content' => $validated['content'],
        ]);

        // Obsługa nowych zdjęć, jeśli zostały przesłane
        if ($request->hasFile('images')) {
            $existingCount = $post->images()->count();
            $newCount      = count($request->file('images'));
            $total         = $existingCount + $newCount;

            // Zabezpieczenie, by nie przekroczyć limitu 3
            if ($total > 3) {
                return back()->withErrors([
                    'images.*' => 'Możesz mieć maksymalnie 3 zdjęcia w jednym poście.',
                ]);
            }

            // Zapis nowych zdjęć
            foreach ($request->file('images') as $uploadedFile) {
                $imagePath = $uploadedFile->store('images', 'public');
                $post->images()->create(['path' => $imagePath]);
            }
        }

        return redirect()->route('posts.index')->with('status', 'Post zaktualizowany.');
    }


    public function toggleFollow(Request $request, User $user){
        $loggedInUser = auth()->user();
        //jesli zalogowany user sledzi autora posta
        if($loggedInUser->isFollowing($user)){
            $loggedInUser->following()->detach($user);
    }else{
        $loggedInUser->following()->attach($user);
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect(route('posts.index'));
    }


    public function user($id, $locale = 'en')
    {
        $user = User::find($id);
        App::setLocale($locale);
        return view('posts.index', [
            'posts' => Post::withCount('usersThatLike')->with('user')->where('user_id',$id)->orderByDesc('users_that_like_count')->paginate(3),
            'user' => $user->name
        ]);
    }


}
