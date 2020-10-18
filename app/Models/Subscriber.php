<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lightspeed_id',
        'createdAt',
        'updatedAt',
        'isConfirmed',
        'email',
        'firstname',
        'lastname',
        'doNotifyConfirmed',
    ];
}
