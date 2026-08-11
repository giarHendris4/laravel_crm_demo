<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Category;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password','role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

        public function categories()
    {
        return $this->belongsToMany(Category::class, 'partner_categories', 'user_id', 'category_id');
    }

        public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function assignedLeads()
    {
        return $this->belongsToMany(Lead::class, 'lead_assignments', 'partner_id', 'lead_id')
                    ->withPivot('assigned_by', 'notes')
                    ->withTimestamps();
    }
}
