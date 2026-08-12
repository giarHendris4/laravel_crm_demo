<?php

namespace App\Models;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'company_name',
        'contact_name',
        'email',
        'phone',
        'opportunity_value',
        'status',
    ];

    // Relasi: Lead ini dimiliki / ditangani oleh satu User (Sales)

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function partners()
    {
        return $this->belongsToMany(User::class, 'lead_assignments', 'lead_id', 'partner_id')
                    ->withPivot('assigned_by', 'notes')
                    ->withTimestamps();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(LeadCategory::class, 'lead_category_id');
    }
}
