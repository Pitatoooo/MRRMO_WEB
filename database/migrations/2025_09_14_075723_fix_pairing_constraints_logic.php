<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixPairingConstraintsLogic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ---- DRIVER-MEDIC PAIRINGS ----
        Schema::table('driver_medic_pairings', function (Blueprint $table) {
            // Drop foreign keys first (to avoid index constraint errors)
            try { $table->dropForeign(['driver_id']); } catch (\Exception $e) {}
            try { $table->dropForeign(['medic_id']); } catch (\Exception $e) {}

            // Now safely drop unique indexes if they exist
            try { $table->dropUnique('unique_driver_date_medic'); } catch (\Exception $e) {}
            try { $table->dropUnique('unique_medic_date_driver'); } catch (\Exception $e) {}
        });

        // ---- DRIVER-AMBULANCE PAIRINGS ----
        Schema::table('driver_ambulance_pairings', function (Blueprint $table) {
            try { $table->dropForeign(['driver_id']); } catch (\Exception $e) {}
            try { $table->dropForeign(['ambulance_id']); } catch (\Exception $e) {}

            try { $table->dropUnique('unique_driver_date_ambulance'); } catch (\Exception $e) {}
            try { $table->dropUnique('unique_ambulance_date_driver'); } catch (\Exception $e) {}
        });

        // ---- Add corrected constraints ----
        Schema::table('driver_medic_pairings', function (Blueprint $table) {
            $table->unique(['medic_id', 'pairing_date'], 'unique_medic_date');
        });

        Schema::table('driver_ambulance_pairings', function (Blueprint $table) {
            $table->unique(['ambulance_id', 'pairing_date'], 'unique_ambulance_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove the new constraints
        Schema::table('driver_medic_pairings', function (Blueprint $table) {
            try { $table->dropUnique('unique_medic_date'); } catch (\Exception $e) {}
        });

        Schema::table('driver_ambulance_pairings', function (Blueprint $table) {
            try { $table->dropUnique('unique_ambulance_date'); } catch (\Exception $e) {}
        });

        // Restore the original unique constraints
        Schema::table('driver_medic_pairings', function (Blueprint $table) {
            $table->unique(['driver_id', 'pairing_date'], 'unique_driver_date_medic');
            $table->unique(['medic_id', 'pairing_date'], 'unique_medic_date_driver');
        });

        Schema::table('driver_ambulance_pairings', function (Blueprint $table) {
            $table->unique(['driver_id', 'pairing_date'], 'unique_driver_date_ambulance');
            $table->unique(['ambulance_id', 'pairing_date'], 'unique_ambulance_date_driver');
        });
    }
}
