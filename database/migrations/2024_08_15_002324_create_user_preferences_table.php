<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('sex_orientation_id')->nullable();
            $table->unsignedBigInteger('gender_id')->nullable();
            $table->unsignedBigInteger('religion_id')->nullable();
            $table->unsignedBigInteger('marital_status_id')->nullable();
            $table->json('looking_for_ids')->nullable(); // Modification pour utiliser un champ JSON
            $table->json('more_about_ids')->nullable();
            $table->json('life_styles')->nullable();
            $table->json('spoken_languages')->nullable();
            $table->integer('min_age')->default(18);
            $table->integer('max_age')->default(99);
            $table->json('interests')->nullable();
            $table->string('country')->nullable();
            $table->integer('max_distance')->nullable();
            $table->timestamps();

            $table->index(['sex_orientation_id', 'gender_id', 'religion_id', 'marital_status_id'], 'user_pref_index');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_preferences');
    }
}
