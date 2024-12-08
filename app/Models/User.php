<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Notifications\ResetPassword;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $table = 'users';

  protected $fillable = [
    'user_type_id',
    'role_id',
    'faculty_id',
    'organization_id',
    'name',
    'email',
    'password',
    'phone_number'
  ];

  public function faculty()
  {
    return $this->belongsTo(Faculty::class, 'faculty_id', 'faculty_id'); // Ganti 'faculty_id' dengan nama kolom yang sesuai
  }

  public function userType()
  {
    return $this->belongsTo(UserType::class, 'user_type_id', 'user_type_id'); // Ganti 'faculty_id' dengan nama kolom yang sesuai
  }

  public function organization()
  {
    return $this->belongsTo(Organization::class, 'organization_id', 'organization_id'); // Ganti 'faculty_id' dengan nama kolom yang sesuai
  }

  public function userRole()
  {
    return $this->belongsTo(UserRole::class, 'role_id', 'role_id'); // Ganti 'faculty_id' dengan nama kolom yang sesuai
  }

  public function supervisor()
  {
    return $this->belongsTo(User::class, 'spv_id'); // Asumsikan 'spv_id' adalah FK ke tabel user
  }
  // Relasi untuk mendapatkan semua user yang memiliki supervisor ini
  public function supervisees()
  {
    return $this->hasMany(Supervisor::class, 'spv_id'); // Mengasumsikan 'spv_id' adalah FK ke tabel supervisors
  }

  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  /**
   * Send the password reset notification.
   *
   * @param  string  $token
   * @return void
   */
  public function sendPasswordResetNotification($token)
  {
    $this->notify(new CustomResetPasswordNotification($token));
  }

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
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
}
