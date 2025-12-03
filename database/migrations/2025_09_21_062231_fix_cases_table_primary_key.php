<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixCasesTablePrimaryKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, check if the table exists and has an 'id' column
        if (Schema::hasTable('cases') && Schema::hasColumn('cases', 'id')) {
            // Drop the existing primary key constraint
            Schema::table('cases', function (Blueprint $table) {
                $table->dropPrimary(['id']);
            });
            
            // Rename the id column to case_num
            Schema::table('cases', function (Blueprint $table) {
                $table->renameColumn('id', 'case_num');
            });
            
            // Add the primary key constraint to case_num
            Schema::table('cases', function (Blueprint $table) {
                $table->primary('case_num');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverse the changes
        if (Schema::hasTable('cases') && Schema::hasColumn('cases', 'case_num')) {
            // Drop the primary key constraint
            Schema::table('cases', function (Blueprint $table) {
                $table->dropPrimary(['case_num']);
            });
            
            // Rename case_num back to id
            Schema::table('cases', function (Blueprint $table) {
                $table->renameColumn('case_num', 'id');
            });
            
            // Add the primary key constraint back to id
            Schema::table('cases', function (Blueprint $table) {
                $table->primary('id');
            });
        }
    }
}
