<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lead_id',
        'company_name',
        'contact_name',
        'email',
        'phone',
        'address',
        'status',
        'total_lifetime_value',
        'notes',
    ];

    /**
     * Relasi ke User (Sales / Owner akun customer ini).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi opsional ke Lead asal-usul konversi.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
