<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class File extends Model
{
    use HasFactory;
   

    protected $fillable = [
        'label',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'user_id',
        'manager_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
