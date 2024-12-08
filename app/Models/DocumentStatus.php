<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentStatus extends Model
{
    use HasFactory;

    protected $table = 'document_status';
    protected $primaryKey = 'status_id';

    protected $fillable = [
        'status_id',
        'status_description',
        'created_at',
        'updated_at',
    ];

    public function documentSubmission()
    {
        return $this->hasMany(DocumentSubmission::class, 'status_id');
    }
}
