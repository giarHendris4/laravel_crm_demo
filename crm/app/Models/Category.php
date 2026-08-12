<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Kategori bisnis partner (Asuransi, KPR, dll).
 * Dihubungkan ke User (partner) melalui tabel pivot `partner_categories`.
 *
 * Beda dengan LeadCategory yang dipakai untuk mengkategorikan Lead.
 */
class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Partner (User berperan 'partner') yang terhubung ke kategori ini.
     */
    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'partner_categories', 'category_id', 'user_id')
                    ->withTimestamps();
    }
}
