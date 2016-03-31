<?php 

namespace app\models;

use User;
use app\models\Faults;
use Eloquent;

class FaultType extends Eloquent {

    public function employees() {
        return $this->belongsToMany('app\models\Employee');
    }

    public function faults() {
        return $this->hasMany('app\models\Fault');
    }

}