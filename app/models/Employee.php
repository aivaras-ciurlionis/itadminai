<?php

namespace app\models;

use User;
use app\models\Fault;
use Eloquent;

class Employee extends Eloquent
{   
    protected $fillable = ['salary'];
    
    public function user(){
        return $this->belongsTo('User');        
    }
    
    public function faults(){
        return $this->hasMany('app\models\Fault');
    }
    
    public function faultTypes(){
        return $this->belongsToMany('app\models\FaultType');
    }
    
     public function faultTypeNames(){
        return $this->belongsToMany('app\models\FaultType')->select('name');
    }
    
    
}
