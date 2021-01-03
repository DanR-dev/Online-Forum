<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profile;

class Profile extends Model
{
    use HasFactory;

    public function posts() // has written any number of posts
    {
        return $this->hasMany('App\Models\Post');
    }

    public function comments() // has written any number of comments
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function user() // has exactly 1 authenticatable account
    {
        return $this->hasOne('App\Models\User');
    }

    public function roles() // holds any number of arbitrary roles
    {
        return $this->belongsToMany('App\Models\Role');
    }
}