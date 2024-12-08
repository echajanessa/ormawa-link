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
        Schema::create('document_submissions', function (Blueprint $table) {
            $table->id('submission_id');
            $table->string('doc_type_id', 15);
            $table->unsignedBigInteger('user_id');
            $table->string('status_id', 15);
            $table->string('event_name');
            $table->string('project_leader');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('organization');
            $table->date('submission_date');
            $table->timestamps();

            $table->foreign('doc_type_id')->references('doc_type_id')->on('document_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('status_id')->references('status_id')->on('document_status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_submissions');
    }
};
