<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    protected $table = 'document_types';
    protected $primaryKey = 'doc_type_id';

    protected $fillable = [
        'document_type',
        'created_at',
        'updated_at'
    ];
}
