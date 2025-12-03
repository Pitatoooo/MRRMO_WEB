<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddAmbulanceIdToCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cases', function (Blueprint $table) {
            if (!Schema::hasColumn('cases', 'ambulance_id')) {
                $table->unsignedBigInteger('ambulance_id')->nullable()->after('contact_number');
            }

            // Add foreign key only if not already present
            $hasForeign = false;
            try {
                $connection = Schema::getConnection();
                $schemaManager = $connection->getDoctrineSchemaManager();
                $doctrineTable = $schemaManager->listTableDetails($connection->getTablePrefix().'cases');
                foreach ($doctrineTable->getForeignKeys() as $fk) {
                    if (in_array('ambulance_id', $fk->getLocalColumns(), true)) {
                        $hasForeign = true;
                        break;
                    }
                }
            } catch (\Throwable $e) {
                // Fallback for MySQL: check information_schema for existing FK on ambulance_id
                try {
                    $exists = DB::selectOne("
                        SELECT 1 FROM information_schema.KEY_COLUMN_USAGE
                        WHERE TABLE_SCHEMA = DATABASE()
                          AND TABLE_NAME = 'cases'
                          AND COLUMN_NAME = 'ambulance_id'
                          AND REFERENCED_TABLE_NAME IS NOT NULL
                        LIMIT 1
                    ");
                    $hasForeign = $exists !== null;
                } catch (\Throwable $ignored) {
                    // ignore
                }
            }

            if (!$hasForeign) {
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
        Schema::table('cases', function (Blueprint $table) {
            // Drop FK if exists
            try {
                $table->dropForeign(['ambulance_id']);
            } catch (\Throwable $e) {
                // ignore if missing
            }
            if (Schema::hasColumn('cases', 'ambulance_id')) {
                $table->dropColumn('ambulance_id');
            }
        });
    }
}
