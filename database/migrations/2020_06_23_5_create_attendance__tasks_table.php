<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTasksTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-attendance')->create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('attendance.attendances');
            $table->foreignId('type_id')->constrained('app.catalogues');
            $table->text('description')->nullable();
            $table->unsignedDouble('percentage_advance')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-attendance')->dropIfExists('tasks');
    }
}
