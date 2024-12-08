<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_files', function (Blueprint $table) {
            $table->id('document_id');
            $table->unsignedBigInteger('submission_id')->nullable();
            $table->unsignedBigInteger('approval_id')->nullable();
            $table->string('document_type');
            $table->unsignedBigInteger('uploaded_by');
            $table->string('file_path');
            $table->string('document_desc');
            $table->timestamps();

            $table->foreign('submission_id')->references('submission_id')->on('document_submissions')->onDelete('cascade');
            $table->foreign('approval_id')->references('approval_id')->on('document_approvals')->onDelete('cascade');
            $table->foreign('document_type')->references('doc_type_id')->on('document_types')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_files');
    }
};
