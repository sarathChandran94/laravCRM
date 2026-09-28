<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'address',
        'status',
    ];
}
