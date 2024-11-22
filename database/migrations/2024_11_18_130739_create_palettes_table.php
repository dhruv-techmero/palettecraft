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
        Schema::create('palettes', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->longText('user_id');
            $table->string('palette_code', 50)->unique(); // Unique identifier
            $table->string('color_1', 7); // Hex code for color 1
            $table->string('color_2', 7);
            $table->string('color_3', 7);
            $table->string('color_4', 7);
            $table->longText('tags');
            $table->integer('likes_count')->default(0); // Count of likes
            $table->timestamps();

            // Foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('palettes');
    }
};
