<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
