<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    protected $table = 'user_types';
    protected $primaryKey = 'user_type_id';
    public $incrementing = false; // Non-auto-increment
    protected $keyType = 'string'; // Tipe primary key adalah string

    protected $fillable = [
        'user_type_id',
        'type_description',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'user_type_id', 'user_type_id');
    }
}
