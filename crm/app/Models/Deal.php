<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lead_id',
        'title',
        'deal_value',
        'stage',
        'expected_close_date',
    ];

    protected function casts(): array
    {
        return [
            'deal_value'          => 'decimal:2',
            'expected_close_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}