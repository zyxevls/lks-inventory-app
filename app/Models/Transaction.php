<?php

namespace App\Models;

use App\Models\User;
use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_number',
        'type',
        'total',
        'notes',
        'transaction_date'
    ];

    protected $casts = [
        'transaction_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function generateInvoice()
    {
        $last = static::latest()->first();
        $number = $last ? ((int) substr($last->invoice_number, -4)) + 1 : 1;
        return 'INV' . date('Ymd') . "-" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
