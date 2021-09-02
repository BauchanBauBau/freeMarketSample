<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $guarded = ['id'];

    public function item(){
        return $this->belongsTo('App\Item');
    }

    public function userBuyer(){
        return $this->belongsTo('App\user', 'buyer_id');
    }

    public function userSeller(){
        return $this->belongsTo('App\user', 'seller_id');
    }
}
