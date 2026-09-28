<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqEntry extends Model
{
    protected $fillable = ['group', 'question', 'answer', 'position', 'published'];

    protected $casts = ['published' => 'boolean'];
}
