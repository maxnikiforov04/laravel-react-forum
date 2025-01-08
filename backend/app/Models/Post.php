<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function users()
    {
        return $this->hasOne(User::class);
    }

    public function communities()
    {
        return $this->hasOne(Community::class);
    }

}
