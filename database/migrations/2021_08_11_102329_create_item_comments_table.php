<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('item_id');
            $table->integer('user_id'); //出品者id
            $table->integer('watcher_id'); //コメントしたユーザーのid
            $table->integer('commentTo_id')->nullable(); //出品者が誰に対してコメントを送るか
            $table->string('comment');
            $table->integer('commentDelete');
            $table->integer('buyed'); //販売済みの場合は1
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
        Schema::dropIfExists('item_comments');
    }
}
