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
     * Relationship to the user who created this user
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship to the user who updated this user
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relationship to FtiData
     */
    public function ftiData()
    {
        return $this->hasMany(FtiData::class, 'user_id', 'nim');
    }

    /**
     * Relationship to Tugas Akhir
     */
    public function tugasAkhirs()
    {
        return $this->hasMany(MahasiswaTugasAkhir::class, 'mahasiswa', 'nim');
    }

    /**
     * Relationship to KP Requests
     */
    public function kpRequests()
    {
        return $this->hasMany(KpRequest::class, 'mahasiswa_id', 'nim');
    }

    /**
     * Relationship to MBKM Registrations
     */
    public function mbkmRegistrations()
    {
        return $this->hasMany(PendaftaranMbkm::class, 'mahasiswa_id', 'nim');
    }

    /**
     * Relationship to TA Sidang Akhirs
     */
    public function taSidangAkhirs()
    {
        return $this->hasMany(TaSidangAkhir::class, 'mahasiswa', 'nim');
    }

    /**
     * Relationship to MBKM Mitras
     */
    public function mbkmMitras()
    {
        return $this->hasMany(MbkmMitra::class, 'created_by', 'id');
    }

    /**
     * Relationship to TA Seminar Proposals
     */
    public function taSeminarProposals()
    {
        return $this->hasMany(TaSeminarProposal::class, 'mahasiswa', 'nim');
    }

    /**
     * Relationship to TA Seminar Hasils
     */
    public function taSeminarHasils()
    {
        return $this->hasMany(TaSeminarHasil::class, 'mahasiswa', 'nim');
    }

    /**
     * Relationship to MK Konversis
     */
    public function mkKonversis()
    {
        return $this->hasMany(MkKonversi::class, 'created_by', 'id');
    }

    /**
     * Relationship to Pendaftaran MBKM Nonmitra
     */
    public function mbkmNonmitraRegistrations()
    {
        return $this->hasMany(PendaftaranMbkmNonmitra::class, 'created_by', 'id');
    }

    /**
     * Relationship to KP Aktivitas
     */
    public function kpAktivitas()
    {
        return $this->hasMany(KpAktivitas::class, 'mahasiswa_id', 'nim');
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
