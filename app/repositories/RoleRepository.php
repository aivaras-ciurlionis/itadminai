<?php 

namespace app\repositories;

use User;
use app\models\Role;
use app\models\Customer;
use app\models\Employee;
use app\models\FaultType;

class RoleRepository {
    public function addToRole(User $user, $roleName) {
        $role = Role::where('name', $roleName)->first();

        $role->users()->save($user);

        $role->save();

        if ($roleName === "Customer") {
            $this->createCustomer($user);
        }

        if ($roleName === "Employee") {
            $this->createEmployee($user);
        }
    }

    public function createCustomer(User $user) {
        $customer = Customer::create(['city' => '', 'country' => '']);
        $customer->user_id = $user->id;
        $customer->save();
    }

    public function createEmployee(User $user) {
        $employee = Employee::create(['salary' => 0]);
        $employee->user_id = $user->id;
        $employee->is_active = true;
        $employee->save();
    }

    public function saveSpecializations(Employee $employee, $specializations) {
        foreach($specializations as $value) {
            $faultType = FaultType::where('name', $value)->first();
            $faultType->employees()->save( $employee);
        }
        $employee->save();
    }


}