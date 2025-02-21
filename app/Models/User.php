<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

// class User extends Authenticatable implements FilamentUser
class User extends Authenticatable implements MustVerifyEmail, FilamentUser

{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nik',
        'name',
        'email',
        'address',
        'number',
        'bank_name',
        'bank_number',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

     public function canAccessPanel(Panel $panel): bool
     {
         return true;
     }

    public function deposit(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    public function createdDeposit(): HasMany
    {
        return $this->hasMany(Deposit::class, 'created_by');
    }

    public function updatedDeposit(): HasMany
    {
        return $this->hasMany(Deposit::class, 'updated_by');
    }

    public function oilData(): HasOne
    {
        return $this->hasOne(OilData::class);
    }

    public function redeem(): HasMany
    {
        return $this->hasMany(Redeem::class);
    }

}
