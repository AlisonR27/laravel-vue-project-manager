<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Removed because of compatibility issues
//        DB::unprepared('
//            CREATE TRIGGER project_status_propagation
//            AFTER UPDATE ON projects
//            FOR EACH ROW
//            BEGIN
//                IF NEW.status = "Inactive" THEN OLD.status <> "Inactive" THEN
//                    UPDATE tasks
//                    SET status = "Inactive"
//                    WHERE project_id = OLD.project_id
//                END IF;
//            END
//        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS project_status_propagation');
    }
};
