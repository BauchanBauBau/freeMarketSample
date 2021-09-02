<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dealing_status extends Model
{
    protected $guarded = ['id'];

    public function item(){
        return $this->belongsTo('App\Item');
    }

    /*
    外部キーのカラム名を任意のものにする場合，
    参照元のカラム名を「○○_id」とし，
    モデルのbelongsToの第二引数にそのカラム名を記載する．
    */
    public function buyer(){
        return $this->belongsTo('App\User', 'buyer_id');
    }

    public function seller(){
        return $this->belongsTo('App\User', 'seller_id');
    }
}
