<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title', 100);
            $table->unsignedTinyInteger('rooms');
            $table->unsignedTinyInteger('guests_number');
            $table->unsignedTinyInteger('bathrooms');
            $table->unsignedSmallInteger('sqm')->nullable();
            $table->string('region');
            $table->string('city');
            $table->string('address');
            $table->unsignedSmallInteger('number');
            $table->double('latitude', 11, 8);
            $table->double('longitude', 11, 8);
            $table->string('cover')->nullable();
            $table->boolean('visibility');
            $table->string('slug')->unique();
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
        Schema::dropIfExists('apartments');
    }
}
