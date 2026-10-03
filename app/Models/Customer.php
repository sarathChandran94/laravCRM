<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Deal;

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

    public function deals() {
        return $this->hasMany(Deal::class);
    }
}
