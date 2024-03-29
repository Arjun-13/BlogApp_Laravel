<?php

namespace App;

use App\User;
Use App\Post;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function post() {
        return $this->belongsTo(Post::class);
    }
}
