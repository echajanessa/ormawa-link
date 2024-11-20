<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSubmissionIdColumn extends Migration
{
    public function up()
    {
        Schema::table('document_submissions', function (Blueprint $table) {
            // Drop foreign key constraints if any
            $table->dropForeign(['submission_id']); // Ganti dengan nama constraint jika ada

            // Modify submission_id column
            $table->string('submission_id', 15)->change();
        });

        // Jika ada tabel lain yang menggunakan submission_id sebagai FK
        Schema::table('related_table_name', function (Blueprint $table) {
            $table->string('submission_id', 15)->change();
        });
    }

    public function down()
    {
        Schema::table('document_submissions', function (Blueprint $table) {
            $table->bigInteger('submission_id')->change();
        });

        Schema::table('related_table_name', function (Blueprint $table) {
            $table->bigInteger('submission_id')->change();
        });
    }
}
