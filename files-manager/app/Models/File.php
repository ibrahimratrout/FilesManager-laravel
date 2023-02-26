<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
   

    protected $fillable = [
        'label',
        'file_path',
        'file_size',
        'file_type',
        'user_id',
    ];

}
