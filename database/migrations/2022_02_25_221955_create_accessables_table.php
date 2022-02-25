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
        Schema::create('accessables', function (Blueprint $table) {
            $table->id();
            $table->uuidMorphs('model');
            $table->uuid('user_id');
            $table->jsonb('abilities')->nullable();
            $table->timestamps();

            $table->unique(['model_type', 'model_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accessables');
    }
};
