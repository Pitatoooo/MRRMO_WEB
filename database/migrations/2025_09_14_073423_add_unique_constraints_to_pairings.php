<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintsToPairings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add unique constraints to driver_medic_pairings table
        Schema::table('driver_medic_pairings', function (Blueprint $table) {
            // Ensure a driver can only be paired with one medic per date
            $table->unique(['driver_id', 'pairing_date'], 'unique_driver_date_medic');
            // Ensure a medic can only be paired with one driver per date
            $table->unique(['medic_id', 'pairing_date'], 'unique_medic_date_driver');
        });

        // Add unique constraints to driver_ambulance_pairings table
        Schema::table('driver_ambulance_pairings', function (Blueprint $table) {
            // Ensure a driver can only be paired with one ambulance per date
            $table->unique(['driver_id', 'pairing_date'], 'unique_driver_date_ambulance');
            // Ensure an ambulance can only be paired with one driver per date
            $table->unique(['ambulance_id', 'pairing_date'], 'unique_ambulance_date_driver');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove unique constraints from driver_medic_pairings table
        Schema::table('driver_medic_pairings', function (Blueprint $table) {
            $table->dropUnique('unique_driver_date_medic');
            $table->dropUnique('unique_medic_date_driver');
        });

        // Remove unique constraints from driver_ambulance_pairings table
        Schema::table('driver_ambulance_pairings', function (Blueprint $table) {
            $table->dropUnique('unique_driver_date_ambulance');
            $table->dropUnique('unique_ambulance_date_driver');
        });
    }
}
