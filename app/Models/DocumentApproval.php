<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentApproval extends Model
{
    use HasFactory;

    protected $table = 'document_approvals';
    protected $primaryKey = 'approval_id';

    protected $fillable = [
        'submission_id',
        'approver_id',
        'status_id',
        'approval_date',
        'comments',
        'created_at',
        'updated_at'
    ];

    // Relasi dengan model DocumentSubmission (relasi many-to-one, satu submission bisa memiliki banyak approvals)
    public function documentSubmission()
    {
        return $this->belongsTo(DocumentSubmission::class, 'submission_id', 'submission_id');
    }

    // Relasi dengan model User (relasi many-to-one, satu user adalah approver dari approval)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id', 'id');
    }

    // Relasi dengan model DocumentStatus (untuk mengetahui status approval)
    public function documentStatus()
    {
        return $this->belongsTo(DocumentStatus::class, 'status_id', 'status_id');
    }

    // Relasi dengan model DocumentFile (satu approval mungkin terhubung dengan beberapa file)
    public function documentFiles()
    {
        return $this->hasMany(DocumentFile::class, 'approval_id', 'approval_id');
    }
}
