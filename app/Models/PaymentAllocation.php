<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAllocation extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['transaction_id', 'student_id', 'period_id', 'nominal_dibayar'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function period()
    {
        return $this->belongsTo(KasPeriod::class);
    }
}
