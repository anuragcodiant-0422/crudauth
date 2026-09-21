<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'amount',
        'spent_at',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'spent_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
