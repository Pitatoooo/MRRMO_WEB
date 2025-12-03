<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnhancedFieldsToDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Contact Information
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('emergency_contact_name')->nullable()->after('address');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            
            // Professional Information
            $table->string('license_number')->nullable()->after('emergency_contact_phone');
            $table->date('license_expiry')->nullable()->after('license_number');
            $table->string('employee_id')->nullable()->after('license_expiry');
            $table->date('hire_date')->nullable()->after('employee_id');
            
            // Profile Information
            $table->string('photo')->nullable()->after('hire_date');
            $table->date('date_of_birth')->nullable()->after('photo');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            
            // Status and Availability
            $table->enum('availability_status', ['online', 'offline', 'busy', 'on_break'])->default('offline')->after('gender');
            $table->timestamp('last_seen_at')->nullable()->after('availability_status');
            $table->boolean('is_available')->default(true)->after('last_seen_at');
            
            // Certifications and Skills
            $table->json('certifications')->nullable()->after('is_available');
            $table->json('skills')->nullable()->after('certifications');
            $table->text('notes')->nullable()->after('skills');
            
            // Add ambulance_id if it doesn't exist
            if (!Schema::hasColumn('drivers', 'ambulance_id')) {
                $table->unsignedBigInteger('ambulance_id')->nullable()->after('notes');
                $table->foreign('ambulance_id')->references('id')->on('ambulances')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'address',
                'emergency_contact_name',
                'emergency_contact_phone',
                'license_number',
                'license_expiry',
                'employee_id',
                'hire_date',
                'photo',
                'date_of_birth',
                'gender',
                'availability_status',
                'last_seen_at',
                'is_available',
                'certifications',
                'skills',
                'notes'
            ]);
        });
    }
}