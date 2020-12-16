<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Comment extends Model
{
    use HasFactory;

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }

    public function commentable()
    {
        return $this->morphTo();
    }
    
    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }
}