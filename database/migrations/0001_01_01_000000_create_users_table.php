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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('nick_name')->nullable();
            $table->string('phone')->default('');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->date('dob')->nullable();
            $table->text('bio')->nullable();
            $table->string('cm_firebase_token')->nullable();
            $table->string('login_medium');
            $table->string('social_id');
            $table->tinyInteger('status')->default(0);
            $table->decimal('coin_balance',24,0)->default(0);
            $table->string('ref_code',10);
            $table->bigInteger('ref_by')->nullable();
            $table->string('current_lang');
            $table->string('type')->default('true');
            $table->string('position')->nullable();
            $table->string('location')->nullable();
            $table->string('relationship_status')->nullable();
            $table->integer('height')->nullable();
            $table->string('education')->nullable();
            $table->string('profession')->nullable();
            $table->string('company')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
