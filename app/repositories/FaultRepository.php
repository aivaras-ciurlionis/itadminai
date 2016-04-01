<?php 

namespace app\repositories;

use User;
use app\models\Employee;
use app\models\Customer;
use app\models\Fault;
use app\models\FaultType;
use Carbon\Carbon;

class FaultRepository {

    public function getCountById($id, $array){
          foreach ($array as $value){
              if ($value->id === $id){
                  return $value->count;
              }
          }
          return -1;        
    }

    public function createNewFault(Customer $customer, $title,
    $description, $os, $type) {
        
        $faultType = FaultType::where('name', '=', $type)->first();
        
        
        $fault = new Fault();
        $fault->title = $title;
        $fault->fault_type_id = $faultType->id;
        $fault->description = $description;
        $fault->operating_system = $os;
        $fault->state = 'registered';
        $fault->customer_id = $customer->id;

        //$freeEmployee = Employee::join('faults', 'fault.employee_id', '=', 'employee.id')->groupBy('employee_id')->selectRaw('count(*) as count')->where('count', 0)->first();

        // Firstly we check for an employee with required specialization and  with no jobs
        $withAllFaults = Employee::selectRaw('employees.id, count(faults.employee_id) as count')
        ->join('employee_fault_type', 'employees.id', '=', 'employee_fault_type.employee_id')
        ->leftJoin('faults', 'faults.employee_id', '=', 'employees.id')
        ->where('employee_fault_type.fault_type_id', '=', $faultType->id)
        ->groupBy('employees.id')->get();
        
         $withFixedFaults = Employee::selectRaw('employees.id, count(faults.employee_id) as count')
        ->join('employee_fault_type', 'employees.id', '=', 'employee_fault_type.employee_id')
        ->leftJoin('faults', 'faults.employee_id', '=', 'employees.id')
        ->whereRaw('`faults`.`state` = \'fixed\'')
        ->where('employee_fault_type.fault_type_id', '=', $faultType->id)
        ->groupBy('employees.id')->get();
        
        $freeEmployee = null;

        foreach ($withAllFaults as $key => $value) {
            if ($value->count == 0){
                $freeEmployee = $value;
                break;
            }
            
            if ($value->count -$this->getCountById($value->id,$withFixedFaults) <= 0){
                $freeEmployee = $value;
                break;                
            }            
        }                     

        $selectedEmployee = null;
      
        if ($freeEmployee == null) {       
            $employeeOldesAsigned = Employee::selectRaw('id, min(last_asignment) as min')
            ->join('employee_fault_type', 'employees.id', '=', 'employee_fault_type.employee_id')
            ->where('last_asignment', '=', 'min')
            ->where('employee_fault_type.fault_type_id', '=', $faultType->id)            
            ->first();        
            $selectedEmployee = $employeeOldesAsigned;           
        } else {
            $selectedEmployee = $freeEmployee;
        }        
        
        
        if ($selectedEmployee == null){
            $selectedEmployee = Employee::first();
        }
        
        $fault->employee_id = $selectedEmployee->id;
        $fault->save();       
        
        $mytime = Carbon::now();
        
        $employee = Employee::find($selectedEmployee->id);
        $employee->last_asignment = $mytime->toDateTimeString();
        $employee->salary = 35;  
        $employee->save();
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

    public function userIsInRole($roles, $roleName) {
        foreach($roles as $role) {
            if ($roleName === $role->name) {
                return true;
            }
        }
        return false;
    }

    public function getAllFaultsFor(User $user, $type) {
        if ($this->userIsInRole($user->roles, "SysAdmin") && $type === "all") {
            return Fault::selectRaw('*');
        } else if ($this->userIsInRole($user->roles, "Employee") && $type === "asigned") {
            return Fault::where('employee_id', $user->employee->id);
        } else if ($this->userIsInRole($user->roles, "Customer") && $type === "created") {
            return Fault::where('customer_id', $user->customer->id);
        }
    }

    public function getAllQueryFaultsFor(User $user, $type, $field, $direction, $search, $stateSearch) {

        $sortDirection = 'ASC';
        if ($direction === 'DESC') {
            $sortDirection = 'DESC';
        }

        if ($this->userIsInRole($user->roles, "SysAdmin") && $type === "all") {
            $faults = Fault;
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

        if ($this->userIsInRole($user->roles, "SysAdmin")) {
            $fault = Fault::find($id);
            if (!$fault) {
                return false;
            }
            return $fault;
        }

        if ($this->userIsInRole($user->roles, "Employee")) {
            $fault = $user->employee->faults->find($id);
            if (!$fault) {
                return false;
            }

            return $fault;
        }

        if ($this->userIsInRole($user->roles, "Customer")) {
            $fault = $user->customer->faults->find($id);
            if (!$fault) {
                return false;
            }

            return $fault;
        }
        
        return false;
    }

    public function updateFault(User $user, $id, $newStatus) {
        $fault = $user->employee->faults->find($id);
        if (!$fault) {
            return false;
        }        
        
        $nowFormat = date('Y-m-d H:i:s');
        
        $oldState = $fault->state;
        if ($oldState === 'registered' && $newStatus === 'inProgress'){
            $fault->started_fixing = $nowFormat;            
            $time1 = strtotime($fault->started_fixing);         
            $time2 = strtotime($fault->created_at);            
            $fault->reaction_time += ( $time1-$time2);
        }    
        
        if ($oldState === 'reopened' && $newStatus === 'inProgress'){
            $fault->started_fixing = $nowFormat;            
            $time1 = strtotime($fault->started_fixing);         
            $time2 = strtotime($fault->reopened_time);            
            $fault->reaction_time += ( $time1-$time2);
        }                  
        
        if ($oldState === 'inProgress' && $newStatus === 'fixed'){
            $fault->finished_fixing = $nowFormat;            
            $time1 = strtotime($fault->finished_fixing);         
            $time2 = strtotime($fault->started_fixing);            
            $fault->fixing_time += ( $time1-$time2);
        }        
        
        $fault->state = $newStatus;
        $fault->save();
        return true;
    }
    
    public function deleteFault(User $user, $faultId){   
        
        $faultToRemove = null;
        
        if ($this->userIsInRole($user->roles, "SysAdmin")) {
            $fault = Fault::find($id);
            if (!$fault) {
                return false;
            }
            $faultToRemove = $fault;
        }             
        
                   
        if ($this->userIsInRole($user->roles, "Customer")) {
             $fault = $user->customer->faults->find($faultId);
             if (!$fault) {
                return false;
            }
            $faultToRemove = $fault;
        
        }
        $faultToRemove->delete();       
        return true;          
    }     
    
    public function reopenFault(User $user, $faultId){               
        $fault = $user->customer->faults->find($faultId);
        
        if ($fault == null){
            return false;
        }        
        
        $fault->state = 'reopened';
        $nowFormat = date('Y-m-d H:i:s');
        $fault->reopened_time = $nowFormat;
        $fault->save();      
        return true;          
    }     
    
    
    

    public function getAllFaultTypes() {
        return FaultType::get();
    }
    
    public function getAllFaultTypesForUser(User $user) {
        return $user->employee->faultTypes->lists('name');
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