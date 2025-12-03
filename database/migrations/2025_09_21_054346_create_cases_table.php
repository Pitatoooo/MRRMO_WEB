<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id('case_num'); // Auto increment primary key
            $table->string('status')->nullable(); // Can be null
            $table->string('name');
            $table->string('contact');
            $table->string('type')->nullable(); // Can be null
            $table->text('address');
            $table->string('destination')->nullable(); // Can be null
            $table->decimal('latitude', 10, 8)->nullable(); // Can be null
            $table->decimal('longitude', 11, 8)->nullable(); // Can be null
            $table->timestamp('timestamp');
            $table->string('driver')->nullable(); // Can be null
            $table->string('contact_number')->nullable(); // Can be null
            $table->unsignedBigInteger('ambulance_id')->nullable(); // Foreign key to ambulances table
            $table->timestamps();
            
            // Add foreign key constraint
            $table->foreign('ambulance_id')->references('id')->on('ambulances')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cases');
    }
}
