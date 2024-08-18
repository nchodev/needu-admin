<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('full_name');
            $table->string('nick_name')->nullable();
            $table->string('phone')->default('');
            $table->string('email')->unique();
            $table->date('dob')->nullable();
            $table->text('bio')->nullable();
            $table->string('current_lang');
            $table->string('type')->default('true');
            $table->string('position')->nullable();
            $table->string('location')->nullable();
            $table->integer('height')->nullable();
            $table->string('profession')->nullable();
            $table->string('company')->nullable();
            $table->string('education')->nullable();
            $table->timestamps();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_infos');
    }
};
