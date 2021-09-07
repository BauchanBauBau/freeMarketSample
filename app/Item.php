<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public static $rules = array(
        'price' => 'required|integer|min:0',
        'days' => 'required|integer|between:0,7',
    );

    protected $guarded = ['id'];
    
    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}