<?php 

namespace app\repositories;

use User;
use app\models\Employee;
use app\models\Customer;
use app\models\Fault;


class FaultRepository {
    public function createNewFault(Customer $customer, $title,
    $description, $os, $type) {
        $fault = new Fault();
        $fault->title = $title;
        $fault->type = $type;
        $fault->description = $description;
        $fault->operating_system = $os;
        $fault->state = 'registered';
        $fault->customer_id = $customer->id;
        
        //$freeEmployee = Employee::join('faults', 'fault.employee_id', '=', 'employee.id')->groupBy('employee_id')->selectRaw('count(*) as count')->where('count', 0)->first();
        
        $freeEmployee = Employee::selectRaw('employees.id, count(faults.employee_id) as count')  
                     ->leftJoin('faults', 'faults.employee_id', '=', 'employees.id')
                     ->groupBy('employees.id')
                     ->having('count', '=', 0)
                     ->first();      
                     
        if ($freeEmployee == null){
            $employee = Employee::selectRaw('id, min(fixed_faults) as min')->where('fixed_faults', '=', 'min' )->first();
            $fault->employee_id = $employee->id;   
        } else {
            $fault->employee_id = $freeEmployee->id;            
        }
        
        $fault->save();
    }

    private function customerHasFault($id, $customer) {
        $faults = $customer->faults;
        foreach($faults as $fault) {
            if ($fault->id === $id) {
                return true;
            }
        }
        return false;
    }
    
    public function userIsInRole($roles, $roleName){    
        foreach ($roles as $role){
            if ($roleName === $role->name){
                return true;
            }   
        }    
        return false;    
    }
        
    public function getAllFaultsFor(User $user, $type){
      if ($this->userIsInRole($user->roles, "SysAdmin") && $type === "all"){ 
            return Fault::get();                       
        } else if ($this->userIsInRole($user->roles, "Employee") && $type === "asigned") {
            return Fault::where('employee_id', $user->employee->id);           
        } else if ($this->userIsInRole($user->roles, "Customer") && $type === "created") {              
            return Fault::where('customer_id', $user->customer->id);            
        }
    }   
    
    public function getAllQueryFaultsFor(User $user, $type, $field, $direction, $search, $stateSearch){
        
        $sortDirection = 'ASC';
        if ($direction === 'DESC') {
            $sortDirection = 'DESC';
        }     
        
        if ($this->userIsInRole($user->roles, "SysAdmin") && $type === "all"){ 
            $faults = Fault::get();                       
        } else if ($this->userIsInRole($user->roles, "Employee") && $type === "asigned") {
            $faults = Fault::where('employee_id', $user->employee->id);           
        } else if ($this->userIsInRole($user->roles, "Customer") && $type === "created") {            
            $faults = Fault::where('customer_id', $user->customer->id);            
        }
        
        if (isset($stateSearch)) {
            $faults = $faults->where('state', $stateSearch);
        }

        if (isset($search)) {
            $faults = $faults->where('title', 'like', $search);
        }

        if (isset($field)) {
            $faults = $faults->orderBy($field, $sortDirection);
        }

        return $faults;
        
        
    }   
    
    
    public function getSingleFault($id, User $user) {
        $exists = false;
        
        if($this->userIsInRole($user->roles, "SysAdmin")){
              $fault = Fault::find($id);
              if(!$fault){
                  return false;
              } 
              
              return $fault;
                    
        }
        
        if($this->userIsInRole($user->roles, "Employee")){
              $fault = $user->employee->faults->find($id);
              if(!$fault){
                  return false;
              } 
              
              return $fault;                    
        }
        
         if($this->userIsInRole($user->roles, "Customer")){
              $fault = $user->customer->faults->find($id);
              if(!$fault){
                  return false;
              } 
              
              return $fault;                    
        }

        
        return false;

    }
    
    public function updateFault(User $user, $id, $newStatus){
        
        $fault = $user->employee->faults->find($id);
        if (!$fault){
            return false;
        }
        $fault->state = $newStatus;
        $fault->save();   
        return true;
        
    }


    public function getAllCustomerFaultsQuery(Customer $customer, $field, $direction, $search, $stateSearch) {
        $sortDirection = 'ASC';
        if ($direction === 'DESC') {
            $sortDirection = 'DESC';
        }

        $faults = Fault::where('customer_id', $customer->id);

        if (isset($stateSearch)) {
            $faults = $faults->where('state', $stateSearch);
        }

        if (isset($search)) {
            $faults = $faults->where('title', 'like', $search);
        }

        if (isset($field)) {
            $faults = $faults->orderBy($field, $sortDirection);
        }

        return $faults;
    }


}