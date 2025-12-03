<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            if (!Schema::hasColumn('cases', 'landmark')) {
                $table->string('landmark')->nullable()->after('address');
            }
            if (!Schema::hasColumn('cases', 'to_go_to_address')) {
                $table->string('to_go_to_address')->nullable()->after('destination');
            }
            if (!Schema::hasColumn('cases', 'to_go_to_landmark')) {
                $table->string('to_go_to_landmark')->nullable()->after('to_go_to_address');
            }
            if (!Schema::hasColumn('cases', 'to_go_to_latitude')) {
                $table->decimal('to_go_to_latitude', 11, 8)->nullable()->after('to_go_to_landmark');
            }
            if (!Schema::hasColumn('cases', 'to_go_to_longitude')) {
                $table->decimal('to_go_to_longitude', 11, 8)->nullable()->after('to_go_to_latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            if (Schema::hasColumn('cases', 'to_go_to_longitude')) {
                $table->dropColumn('to_go_to_longitude');
            }
            if (Schema::hasColumn('cases', 'to_go_to_latitude')) {
                $table->dropColumn('to_go_to_latitude');
            }
            if (Schema::hasColumn('cases', 'to_go_to_landmark')) {
                $table->dropColumn('to_go_to_landmark');
            }
            if (Schema::hasColumn('cases', 'to_go_to_address')) {
                $table->dropColumn('to_go_to_address');
            }
            if (Schema::hasColumn('cases', 'landmark')) {
                $table->dropColumn('landmark');
            }
        });
    }
};


