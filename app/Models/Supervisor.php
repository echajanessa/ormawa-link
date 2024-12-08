<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    protected $table = 'supervisors';

    protected $fillable = [
        'user_spv_id',
        'spv_id',
        'user_id',
        'created_at',
        'updated_at'
    ];
    // Relasi untuk mendapatkan user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Menghubungkan ke tabel users
    }

    // Relasi untuk mendapatkan supervisor
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'spv_id'); // Menghubungkan ke tabel users
    }
}
