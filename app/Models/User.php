<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts()
    {
        //relacja 1 do wielu, jeden user ma wiele postow
        return $this->hasMany(Post::class);
    }

    public function image()
    {
        return $this->hasOne(Image::class);
    }

    public function followers(){
        //user nalezy do wielu uzytkownikow ktorzy sa śledzeni
        //followers - nazwa tabeli
        //following_id  - id tego uzytkownika
        //follower_id - id usera ktorzy nas sledza
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }

    //funkcja zwroci uzytkjownikow ktorych ten uzytkownik sledzi
    public function following(){
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }

    //funkvja do sprawdzenia czy sledze autora posta po wejsciu w szczegoly posta
    public function isFollowing($user){
        return $this->following()->where('following_id', $user->getKey())->exists();
    }

    //user moze miec wiele postow ktore lubi/nielubi
    public function likedPosts(){
        return $this->morphedByMany(Post::class, 'likeable');
    }
    public function dislikedPosts(){
        return $this->morphedByMany(Post::class, 'dislikeable');
    }



}
