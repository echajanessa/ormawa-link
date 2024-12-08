<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentFile extends Model
{
    use HasFactory;

    protected $table = 'document_files';
    protected $primaryKey = 'document_id';

    protected $fillable = [
        'submission_id',
        'approval_id',
        'document_type',
        'uploaded_by',
        'file_path',
        'document_desc',
        'created_at',
        'updated_at'
    ];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'doc_type_id', 'document_type');
    }

    public function submission()
    {
        return $this->belongsTo(DocumentSubmission::class, 'submission_id', 'submission_id');
    }
}
