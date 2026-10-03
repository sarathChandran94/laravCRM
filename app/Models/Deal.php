<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Deal extends Model
{
    protected $fillable = [
        "customer_id",
        "title",
        "amount",
        "stage",
        "expected_close_date",
        "notes",
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
