<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepositoryItem extends Model
{
    protected $table = 'repository_items';

    protected $fillable = [
        'type',
        'title',
        'author',
        'year',
        'file_path',
        'description',
        'category',
        'user_id',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
