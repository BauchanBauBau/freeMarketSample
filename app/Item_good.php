<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item_good extends Model
{
    protected $guarded = ['id'];

    public function item(){
        return $this->belongsTo('App\Item');
    }

    /*
    （補足）
    もし外部キーのカラム名を任意のものにする場合，
    参照元のカラム名を「○○_id」とし，
    モデルのbelongsToの第二引数にそのカラム名を記載する．
    */
}
