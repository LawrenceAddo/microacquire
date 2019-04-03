<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socials', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('ref_id');
            $table->smallInteger('ref_type')->default(0); // 0: buyer, 1: selling
            $table->smallInteger('status')->default(0);

            $table->string('social_type', 100)->default(0);
            $table->text('social_url')->nullable();
            $table->integer('seq')->default(0);
            
            $table->unique(['ref_id', 'ref_type', 'social_type'], 'social_item');

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
        Schema::dropIfExists('socials');
    }
}
