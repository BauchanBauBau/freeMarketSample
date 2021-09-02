<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_goods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('watcher_id'); //「いいね」したユーザーのid
            $table->integer('user_id'); //出品者
            $table->integer('item_id');
            $table->integer('buyed');
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
        Schema::dropIfExists('item_goods');
    }
}
