<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRegulation extends Model
{
    use HasFactory;
    protected $table = 'document_regulations';
    protected $primaryKey = 'regulation_id';
    public $incrementing = false;

    protected $fillable = [
        'regulation_id',
        'user_id',
        'announcement_title',
        'announcement_description',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true;
}
