<?php 

namespace app\repositories;

use User;
use app\models\Employee;
use app\models\Customer;
use app\models\Fault;
use app\models\FaultType;
use Illuminate\Support\Facades\Hash;

class UsersRepository {

    public function getAllUsers($type) {
        if ($type == 'customers') {
            return Customer::join('users', 'users.id', '=', 'customers.user_id');
        }
        
         if ($type == 'employees') {
            return Employee::join('users', 'users.id', '=', 'employees.user_id');
        }
        
    }

    public function getAllUsersQuery($type, $field, $direction, $search) {
        $sortDirection = 'ASC';
        if ($direction === 'DESC') {
            $sortDirection = 'DESC';
        }

        if ($type == 'customers') {
            $users = Customer::join('users', 'users.id', '=', 'customers.user_id');
        }
        
        if ($type == 'employees') {
            $users = Employee::join('users', 'users.id', '=', 'employees.user_id');
        }

        if (isset($search)) {
            $users = $users->whereRaw('name = \''.$search.'\' or email = \''.$search.'\'');
        }

        if (isset($field)) {
            $users = $users->orderBy($field, $sortDirection);
        }
        
        return $users;        
    }
    
    public function enableUser($userId){
        $user = User::find($userId);
        $user->disabled = false;
        $user->save();
    }
    
    public function disableUser($userId){
        $user = User::find($userId);
        $user->disabled = true;
        $user->save();
    }
    
    public function setNewPasswordForUser($userId, $newPassword){
        $user = User::find($userId);
        $user->password = Hash::make($newPassword);
        $user->save();        
    }
    
    
    






}