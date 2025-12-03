<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDestinationToAmbulancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::table('ambulances', function (Blueprint $table) {
        $table->decimal('destination_latitude', 10, 7)->nullable();
        $table->decimal('destination_longitude', 10, 7)->nullable();
        $table->timestamp('destination_updated_at')->nullable();
    });
}

public function down()
{
    Schema::table('ambulances', function (Blueprint $table) {
        $table->dropColumn(['destination_latitude', 'destination_longitude', 'destination_updated_at']);
    });
}
    
}
