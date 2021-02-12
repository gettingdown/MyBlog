<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    public $timestamps = true;
    public function users(){
        $this->belongsTo(User::Class);
    }
    public function comments(){
        $this->hasMany(Comment::Class);
    }
}
