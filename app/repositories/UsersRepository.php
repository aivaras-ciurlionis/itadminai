<?php 

namespace app\repositories;

use User;
use app\models\Employee;
use app\models\Customer;
use app\models\Fault;
use app\models\FaultType;
use Illuminate\Support\Facades\Hash;

class UsersRepository {
    
    public function getSingleEmployee($id)
    {
         return Employee::selectRaw('*, AVG(`faults`.`reaction_time`) as avg_reaction_time, AVG(`faults`.`fixing_time`) as avg_fixing_time')
            ->join('users', 'users.id', '=', 'employees.user_id')
            ->leftjoin('faults', 'employees.id', '=', 'faults.employee_id')
            ->where('employees.id', '=', $id)
            ->groupBy('employees.id')
            ->first();     
    }
    
    public function getEmployeeSpecializations($id){
        return FaultType::select('name')->join('employee_fault_type', 'fault_types.id', '=', 'employee_fault_type.fault_type_id')
        ->where('employee_fault_type.employee_id', '=', $id)->get();       
    }     
    
    
    public function getAllEmployeesEmails(){
        return Employee::select('email')->join('users', 'users.id', '=', 'employees.user_id')->get();       
    }

    public function getAllUsers($type) {
        if ($type == 'customers') {
            return Customer::selectRaw('*, count(`faults`.`customer_id`) as fault_count')
            ->join('users', 'users.id', '=', 'customers.user_id')
            ->leftJoin('faults', 'customers.id', '=', 'faults.customer_id')
            ->groupBy('customers.id');    
        }
        
         if ($type == 'employees') {
            return Employee::selectRaw('*, AVG(`faults`.`reaction_time`) as avg_reaction_time, AVG(`faults`.`fixing_time`) as avg_fixing_time')
            ->join('users', 'users.id', '=', 'employees.user_id')
            ->leftjoin('faults', 'employees.id', '=', 'faults.employee_id')
            ->groupBy('employees.id');            
        }
        
    }
    

    public function getAllUsersQuery($type, $field, $direction, $search) {
        $sortDirection = 'ASC';
        if ($direction === 'DESC') {
            $sortDirection = 'DESC';
        }

        if ($type == 'customers') {
            $users = Customer::selectRaw('*, count(`faults`.`customer_id`) as fault_count')
            ->join('users', 'users.id', '=', 'customers.user_id')
            ->leftJoin('faults', 'customers.id', '=', 'faults.customer_id')
            ->groupBy('customers.id');            
        }
        
        if ($type == 'employees') {
            $users =  Employee::selectRaw('*, AVG(`faults`.`reaction_time`) as avg_reaction_time, AVG(`faults`.`fixing_time`) as avg_fixing_time')
            ->join('users', 'users.id', '=', 'employees.user_id')
            ->leftjoin('faults', 'employees.id', '=', 'faults.employee_id')
            ->groupBy('employees.id');   
        }

        if (isset($search)) {
            $users = $users->whereRaw('name like \'%'.$search.'%\' or email like \'%'.$search.'%\'');
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
    
    public function enableAsignment($employeeId){
        $user = User::find($employeeId)->employee;
        $user->is_active = true;
        $user->save();
    }
    
    public function disableAsignment($employeeId){
        $user = User::find($employeeId)->employee;
        $user->is_active = false;
        $user->save();
    }    
    
    public function setNewPasswordForUser($userId, $newPassword){
        $user = User::find($userId);
        $user->password = Hash::make($newPassword);
        $user->save();        
    }    
    
     public function createUser($name, $email, $password){
        $user = new User;
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();            
        return $user;
    }
    
}