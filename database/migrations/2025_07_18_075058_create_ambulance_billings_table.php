<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmbulanceBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('ambulance_billings', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('address');
        $table->string('service_type');
        $table->timestamp('date')->useCurrent(); // auto current timestamp
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
        Schema::dropIfExists('ambulance_billings');
    }
}
