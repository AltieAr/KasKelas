<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasPeriod extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'nama_periode',
        'nominal_tagihan',
        'tanggal_jatuh_tempo',
    ];

    public function paymentAllocations()
    {
        return $this->hasMany(PaymentAllocation::class);
    }
}
