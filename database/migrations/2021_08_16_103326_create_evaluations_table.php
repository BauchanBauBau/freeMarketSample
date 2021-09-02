<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('dealingStatus_id');
            $table->integer('item_id');
            $table->integer('seller_id');
            $table->integer('buyer_id');
            $table->integer('sellerEvaluation'); //出品者に対する評価
            $table->integer('buyerEvaluation'); //購入者に対する評価
            $table->string('sellerComment')->nullable(); //出品者に対するコメント
            $table->string('buyerComment')->nullable(); //購入者に対するコメント
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
        Schema::dropIfExists('evaluations');
    }
}
