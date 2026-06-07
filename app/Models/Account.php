<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = "accounts";
    protected $fillable = ['user_id', 'currency_id', 'name', 'balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currencies::class, 'currencies_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
