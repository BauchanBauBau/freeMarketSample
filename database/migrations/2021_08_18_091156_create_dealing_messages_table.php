<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealingMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealing_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('dealingStatus_id');
            $table->integer('seller_id');
            $table->integer('buyer_id');
            $table->integer('user_id'); //質問者
            $table->string('message');
            $table->intager('kidoku');
            $table->integer('messageDelete');
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
        Schema::dropIfExists('dealing_messages');
    }
}
