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
        Schema::create('members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number')->unique();
            $table->text('address')->nullable();
            $table->foreignUuid('subtenant_id')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('subtenant_id')->on('subtenants')->references('id')->onDelete('CASCADE');
        });
        
        Schema::create('persons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number')->unique();
            $table->string('name');
            $table->enum('gender', ['MALE', 'FEMALE'])->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->uuid('member_id')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('member_id')->on('members')->references('id')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persons');
        Schema::dropIfExists('members');
    }
};
