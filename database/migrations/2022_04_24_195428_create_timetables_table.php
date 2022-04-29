<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->string('identifier');
            $table->foreignId('class_room_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('week_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('period_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('occasion_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('timetables');
    }
};
