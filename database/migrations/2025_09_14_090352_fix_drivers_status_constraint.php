<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixDriversStatusConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop existing constraints if they exist
        DB::statement('ALTER TABLE drivers DROP CONSTRAINT IF EXISTS drivers_status_check');
        DB::statement('ALTER TABLE drivers DROP CONSTRAINT IF EXISTS drivers_availability_status_check');
        
        // Add new constraints with correct values
        DB::statement('ALTER TABLE drivers ADD CONSTRAINT drivers_status_check CHECK (status IN (\'active\', \'inactive\', \'suspended\', \'on_leave\'))');
        DB::statement('ALTER TABLE drivers ADD CONSTRAINT drivers_availability_status_check CHECK (availability_status IN (\'online\', \'offline\', \'busy\', \'on_break\'))');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the constraints
        DB::statement('ALTER TABLE drivers DROP CONSTRAINT IF EXISTS drivers_status_check');
        DB::statement('ALTER TABLE drivers DROP CONSTRAINT IF EXISTS drivers_availability_status_check');
    }
}