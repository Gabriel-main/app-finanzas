<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['user_id', 'app_name', 'primary_color', 'chart_income_color', 'chart_expense_color', 'logo_path'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
