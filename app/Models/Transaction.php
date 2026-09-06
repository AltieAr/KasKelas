<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'tipe',
        'nominal',
        'tanggal_transaksi',
        'keterangan',
    ];

    public function paymentAllocations()
    {
        return $this->hasMany(PaymentAllocation::class);
    }
}
