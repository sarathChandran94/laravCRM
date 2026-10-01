<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'source',
        'status',
        'notes',
        'follow_up_date',
    ];
}
