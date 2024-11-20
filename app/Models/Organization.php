<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';
    protected $primaryKey = 'organization_id';
    public $incrementing = false; // Non-auto-increment
    protected $keyType = 'string'; // Tipe primary key adalah string

    protected $fillable = [
        'organization_id',
        'faculty_id',
        'role_id',
        'org_name',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'organization_id', 'organization_id');
    }
}
