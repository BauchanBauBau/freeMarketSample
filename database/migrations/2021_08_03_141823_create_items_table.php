<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id'); //出品者
            $table->string('name'); //商品名
            $table->string('image')->nullable(); //商品の画像
            $table->integer('category_id')->nullable(); //カテゴリー
            $table->string('description')->nullable(); //商品の説明
            $table->integer('condition'); //(選択式)商品の状態(新品，未使用，状態が良い悪い等)
            $table->integer('price'); //値段
            $table->integer('shippingOption'); //(選択式)発送オプション(値段に送料が含まれる・含まれない)
            $table->integer('shippingMethod'); //(選択式)配送方法(普通郵便，レターパック，ゆうパック, ヤマト運輸，佐川急便，その他)
            $table->integer('days'); //発送までの日数
            $table->integer('buyer_id'); //0でなければsoldOut
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
        Schema::dropIfExists('items');
    }
}
