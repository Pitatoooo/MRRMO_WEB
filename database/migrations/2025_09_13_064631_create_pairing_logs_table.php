<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePairingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pairing_logs', function (Blueprint $table) {
            $table->id();
            $table->string('pairing_type'); // 'driver_medic' or 'driver_ambulance'
            $table->unsignedBigInteger('pairing_id'); // ID of the pairing record
            $table->string('action'); // 'created', 'updated', 'deleted'
            $table->json('old_data')->nullable(); // Previous data
            $table->json('new_data')->nullable(); // New data
            $table->unsignedBigInteger('admin_id')->nullable(); // Who made the change
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pairing_logs');
    }
}
