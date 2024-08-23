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
        Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_match_id')->nullable(); // Référence au match
                $table->foreignId('sender_id');
                $table->foreignId('receiver_id');
                $table->text('content'); // Contenu du message
                $table->tinyInteger('one_view')->default(0)->comment('0:inactf, 1:actif, 2: deja vu');
                $table->enum('type',['text','media','voice','match','request','info','gift'])->default('text');
                $table->timestamp('read_at')->nullable(); // Horodatage de la lecture du message
                $table->timestamps(); // Horodatages de création et de mise à jour

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
