<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSubmission extends Model
{
    use HasFactory;

    protected $table = 'document_submissions';
    protected $primaryKey = 'submission_id';
    public $timestamps = true;

    protected $fillable = [
        'doc_type_id',
        'user_id',
        'status_id',
        'event_name',
        'project_leader',
        'start_date',
        'end_date',
        'location',
        'organization',
        'created_at',
        'updated_at'
    ];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'doc_type_id', 'doc_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files()
    {
        return $this->hasMany(DocumentFile::class, 'submission_id', 'submission_id');
    }

    public function documentStatus()
    {
        return $this->belongsTo(DocumentStatus::class, 'status_id');
    }
}
