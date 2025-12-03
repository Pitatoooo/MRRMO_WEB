<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixConstraintsWithStatus extends Migration
{
    public function up()
    {
        // Drop old constraints (ignore if missing)
        Schema::table('driver_medic_pairings', function (Blueprint $table) {
            $hasIndex = function(string $tbl, string $idx): bool {
                try {
                    $rows = DB::select("SHOW INDEX FROM `{$tbl}` WHERE Key_name = ?", [$idx]);
                    return !empty($rows);
                } catch (\Throwable $e) {
                    return false;
                }
            };
            if ($hasIndex('driver_medic_pairings', 'unique_medic_date')) {
                $table->dropUnique('unique_medic_date');
            }
            if ($hasIndex('driver_medic_pairings', 'unique_driver_date_medic')) {
                $table->dropUnique('unique_driver_date_medic');
            }
        });

        Schema::table('driver_ambulance_pairings', function (Blueprint $table) {
            $hasIndex = function(string $tbl, string $idx): bool {
                try {
                    $rows = DB::select("SHOW INDEX FROM `{$tbl}` WHERE Key_name = ?", [$idx]);
                    return !empty($rows);
                } catch (\Throwable $e) {
                    return false;
                }
            };
            if ($hasIndex('driver_ambulance_pairings', 'unique_ambulance_date')) {
                $table->dropUnique('unique_ambulance_date');
            }
            if ($hasIndex('driver_ambulance_pairings', 'unique_driver_date_ambulance')) {
                $table->dropUnique('unique_driver_date_ambulance');
            }
        });

        // Add status column if missing
        if (!Schema::hasColumn('driver_medic_pairings', 'status')) {
            Schema::table('driver_medic_pairings', function (Blueprint $table) {
                $table->string('status')->default('active');
            });
        }

        if (!Schema::hasColumn('driver_ambulance_pairings', 'status')) {
            Schema::table('driver_ambulance_pairings', function (Blueprint $table) {
                $table->string('status')->default('active');
            });
        }

        // Add new constraints
        Schema::table('driver_medic_pairings', function (Blueprint $table) {
            $table->unique(['medic_id', 'pairing_date', 'status'], 'unique_medic_date_status');
        });

        Schema::table('driver_ambulance_pairings', function (Blueprint $table) {
            $table->unique(['ambulance_id', 'pairing_date', 'status'], 'unique_ambulance_date_status');
        });
    }

    public function down()
    {
        Schema::table('driver_medic_pairings', function (Blueprint $table) {
            try { $table->dropUnique('unique_medic_date_status'); } catch (\Exception $e) {}
        });

        Schema::table('driver_ambulance_pairings', function (Blueprint $table) {
            try { $table->dropUnique('unique_ambulance_date_status'); } catch (\Exception $e) {}
        });
    }
}
