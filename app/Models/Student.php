<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory, HasUuids;


    protected $fillable = [
        'nama',
        'no_absen',
    ];

    public function paymentAllocations()
    {
        return $this->hasMany(PaymentAllocation::class);
    }
}
