<?php

namespace app\models;

use User;
use Eloquent;

class Role extends Eloquent
{
    
    protected $table = 'roles';
    
    protected $fillable = ['name'];
    
    public function users()
    {
        return $this->belongsToMany('User');        
    }    
    
}
