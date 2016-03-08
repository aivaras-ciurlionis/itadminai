<?php

namespace app\models;

use User;
use app\models\Fault;
use Eloquent;

class Employee extends Eloquent
{
    public function user(){
        return $this->belongsTo('User');        
    }
    
    public function faults(){
        return $this->hasMany('app\models\Fault');
    }
    
    
}
