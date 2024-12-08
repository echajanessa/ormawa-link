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
        Schema::create('document_approvals', function (Blueprint $table) {
            $table->id('approval_id');
            $table->unsignedBigInteger('submission_id');
            $table->unsignedBigInteger('approver_id');
            $table->string('status_id');
            $table->date('approval_date');
            $table->longText('comments')->nullable();
            $table->timestamps();

            $table->foreign('submission_id')->references('submission_id')->on('document_submissions')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('status_id')->references('status_id')->on('document_status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_approvals');
    }
};
