<?php

namespace app\models;

use User;
use app\models\Faults;
use Eloquent;

class Customer extends Eloquent
{
    
     protected $fillable = ['city', 'country'];
    
    public function user(){
        return $this->belongsTo('User');        
    }
    
    public function faults(){
        return $this->hasMany('app\models\Fault');
    }
    
}
