<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nim',
        'email',
        'password',
        'created_by',
        'updated_by',
        'active',
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

    /**
     * Relationship to UserRole
     */
    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'username', 'nim');
    }

    /**
     * Check if user is Admin
     */
    public function isAdmin()
    {
        return $this->userRoles()->whereHas('role', function ($query) {
            $query->where('name', 'Admin');
        })->exists();
    }

    /**
     * Check if user is Koordinator
     */
    public function isKoordinator()
    {
        return $this->userRoles()->whereHas('role', function ($query) {
            $query->where('name', 'Koordinator');
        })->exists();
    }

    /**
     * Check if user is Dosen
     */
    public function isDosen()
    {
        return $this->userRoles()->whereHas('role', function ($query) {
            $query->where('name', 'Dosen');
        })->exists();
    }

    /**
     * Check if user is Mahasiswa
     */
    public function isMahasiswa()
    {
        return $this->userRoles()->whereHas('role', function ($query) {
            $query->where('name', 'Mahasiswa');
        })->exists();
    }
}
