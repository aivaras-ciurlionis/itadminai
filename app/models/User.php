<?php



use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

use app\models\Role;
use app\models\Customer;
use app\models\Employee;
use app\models\SysAdmin;




class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
    
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
    
        
    public function roles(){
        return $this->belongsToMany('app\models\Role');
    }
    
    public function customer(){
        return $this->hasOne('app\models\Customer');
    }
    
    public function employee(){
        return $this->hasOne('app\models\Employee');
    }
    
    public function sysAdmin(){
        return $this->hasOne('app\models\SysAdmin');
    }

}
