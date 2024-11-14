<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

public function tags()
{
    return $this->belongsToMany(Tag::class);
}

public function getImagePathAttribute()
    {
        return asset('uploads/'.$this->image);
    }

public function category()
{
    return $this->belongsTo(Category::class);
}
}
