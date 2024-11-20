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
        Schema::create('organizations', function (Blueprint $table) {
            $table->string('organization_id', 15)->primary();
            $table->string('faculty_id', 15);
            $table->string('role_id', 15);
            $table->string('org_name');
            $table->timestamps();

            $table->foreign('faculty_id')->references('faculty_id')->on('faculties')->onDelete('cascade');
            $table->foreign('role_id')->references('role_id')->on('user_roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
