<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\PostImage;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'content',
    ];


    public function user(){
        //post nalezy do 1 usera
        return $this->belongsTo(User::class);
    }
    public function usersThatLike(){
        //post ma wielu userow ktorzy lubia
        return $this->morphToMany(User::class, 'likeable');
    }
    public function usersThatDislike(){
        //post ma wielu userow ktorzy nie lubia
        return $this->morphToMany(User::class, 'dislikeable');
    }

    //metoda sprawdzajaca po wejsciu na szczegoly posta czy lubimy/nie lublimy posta
    public function isLiked(){
        //jesli wsrod osob ktore lubia jest zalogowany user to wyswietlany post jest lubiany
        return $this->usersThatLike()->where('user_id', Auth::user()->id)->exists();
    }
    public function isDisLiked(){
        //jesli wsrod osob ktore lubia jest zalogowany user to wyswietlany post jest lubiany
        return $this->usersThatDislike()->where('user_id', Auth::user()->id)->exists();
    }
    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

}
