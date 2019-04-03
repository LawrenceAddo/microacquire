<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSellerProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selling_props', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id');

            $table->string('name', 200)->nullable();
            $table->text('description')->nullable();

            $table->text('metrics')->nullable();
            $table->double('revenue')->nullable();
            $table->date('date_founded')->nullable();
            $table->integer('customers_cnt')->nullable();
            $table->double('price')->nullable();
            
            $table->text('reason')->nullable();;
            $table->text('growth')->nullable();;
            $table->text('highlights')->nullable();;
            $table->text('fi_info')->nullable();;
            $table->text('team')->nullable();;
            $table->text('support')->nullable();;
            $table->text('others')->nullable();;

            $table->text('files')->nullable();;
            $table->integer('files_cnt')->nullable();;
            $table->text('images')->nullable();;
            $table->integer('images_cnt')->nullable();;

            $table->smallInteger('status')->default(0);

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
        Schema::dropIfExists('selling_props');
    }
}
