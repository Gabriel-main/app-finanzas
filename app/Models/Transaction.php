<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transaction extends Model
{
    use HasUuids; // Habilita el manejo automático de UUIDs

    protected $table = "transactions";
    protected $fillable = ['account_id', 'category_id', 'amount', 'type', 'description', 'transaction_date'];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
}
