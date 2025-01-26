<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        //Łączna liczba userów i userów z postami
        $totalUsers = User::count();
        $usersWithPostsCount = User::has('posts')->count();

        // Konta z największą liczbą obserwujących
        $allUsersFollowers = User::withCount('followers')->get();
        $maxFollowersCount = $allUsersFollowers->max('followers_count');
        $usersWithMaxFollowers = $allUsersFollowers->where('followers_count', $maxFollowersCount);

        // Konta z największą liczbą 'following'
        $allUsersFollowing = User::withCount('following')->get();
        $maxFollowingCount = $allUsersFollowing->max('following_count');
        $usersWithMaxFollowing = $allUsersFollowing->where('following_count', $maxFollowingCount);

        //Łączna liczba postów
        $totalPosts = Post::count();

        //Posty z największą liczbą polubień
        $allPostsWithLikes = Post::withCount('usersThatLike')->get();
        $maxLikes = $allPostsWithLikes->max('users_that_like_count');
        $postsWithMaxLikes = $allPostsWithLikes->where('users_that_like_count', $maxLikes);

        //Posty z największą liczbą niepolubień
        $allPostsWithDislikes = Post::withCount('usersThatDislike')->get();
        $maxDislikes = $allPostsWithDislikes->max('users_that_dislike_count');
        $postsWithMaxDislikes = $allPostsWithDislikes->where('users_that_dislike_count', $maxDislikes);

        //widok 'dashboard' (który zawiera partiala 'admin-info.blade.php')
        return view('dashboard', [
            // Nazwy kluczy => zmienne statystyk
            'totalUsers'           => $totalUsers,
            'usersWithPostsCount'  => $usersWithPostsCount,
            'usersWithMaxFollowers'=> $usersWithMaxFollowers,
            'usersWithMaxFollowing'=> $usersWithMaxFollowing,
            'totalPosts'           => $totalPosts,
            'postsWithMaxLikes'    => $postsWithMaxLikes,
            'postsWithMaxDislikes' => $postsWithMaxDislikes,
        ]);
    }
}

