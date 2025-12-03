<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FtiData extends Model
{
    protected $table = 'fti_datas';

    protected $fillable = [
        'nama', 'user_id', 'username', 'nim', 'role', 'role_id', 'prodi', 'fakultas', 'status', 'active', 'created_by', 'updated_by'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    // MBKM Relationships
    public function mbkmRegistrations()
    {
        return $this->hasMany(PendaftaranMbkm::class, 'mahasiswa_id');
    }

    public function mbkmNonmitraRegistrations()
    {
        return $this->hasMany(PendaftaranMbkmNonmitra::class, 'mahasiswa_id');
    }

    public function mbkmPelaksanaans()
    {
        return $this->hasMany(MbkmPelaksanaan::class, 'mahasiswa_id');
    }

    public function seminarMbkm()
    {
        return $this->hasOne(SeminarMbkm::class, 'mahasiswa_id');
    }

    // KP Relationships
    public function kpRequests()
    {
        return $this->hasMany(KpRequest::class, 'mahasiswa_id');
    }

    public function kpGroups()
    {
        return $this->belongsToMany(KpGroup::class, 'kp_group_members', 'mahasiswa_id', 'group_id');
    }

    // Tugas Akhir Relationships
    public function tugasAkhir()
    {
        return $this->hasOne(MahasiswaTugasAkhir::class, 'mahasiswa');
    }

    public function mkKonversis()
    {
        return $this->hasMany(MkKonversi::class, 'mahasiswa_id');
    }

    // Teaching Relationships (for lecturers)
    public function taughtCourses()
    {
        return $this->hasMany(Kurikulum::class, 'dosen_id');
    }

    // Created/Updated by relationships
    public function createdRecords()
    {
        return $this->hasMany(self::class, 'created_by');
    }

    public function updatedRecords()
    {
        return $this->hasMany(self::class, 'updated_by');
    }

}
