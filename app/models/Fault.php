<?php

namespace app\models;

use app\models\Customer;
use app\models\Employee;
use Eloquent;

class Fault extends Eloquent
{
    public function employee()
    {
        return $this->belongsTo('app\models\Employee');        
    }
    
    public function customer()
    {
        return $this->belongsTo('app\models\Customer');        
    }
        
}
