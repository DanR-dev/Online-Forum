<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Comment extends Model
{
    use HasFactory;

    public function profile() // was written by exactly one profile
    {
        return $this->belongsTo('App\Models\Profile');
    }

    public function commentable() // is a comment on exactly one comment OR one post (polymorphic)
    {
        return $this->morphTo();
    }
    
    public function comments() // has recieved any number of comments (polymorphic)
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }
}