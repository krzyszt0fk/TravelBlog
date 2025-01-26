<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class Search extends Component
{
    public $search = '';

    public function render()
    {
        return view('livewire.search', [
            'posts' => $this->search
                ? Post::where('title', 'like', "%{$this->search}%")
                    ->orWhere('content', 'like', "%{$this->search}%")
                    ->get()
                : [],
        ]);
    }

}
