<?php

namespace app\models;
use Eloquent;
class SysAdmin extends Eloquent
{
    public function user(){
        return $this->belongsTo('User');        
    }
}
