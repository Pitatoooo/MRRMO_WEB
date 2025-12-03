<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->boolean('driver_accepted')->default(false)->after('ambulance_id');
            $table->boolean('notification_sent')->default(false)->after('driver_accepted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropColumn(['driver_accepted', 'notification_sent']);
        });
    }
};