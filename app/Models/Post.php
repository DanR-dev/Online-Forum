<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Post extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
    ];

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }
    
    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }
}