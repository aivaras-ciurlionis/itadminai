<?php 

namespace app\repositories;

use app\models\Customer;
use app\models\FaultType;
use User;

class CustomerRepository {
    public function updateDetails(User $user, $name, $city, $country) {
        $user->name = $name;
        $user->save();
        $customer = $user->customer;
        $customer->city = $city;
        $customer->country = $country;
        $customer->save();
    }

    public function arrayDifference($array1, $array2) {
        $result = array();
        foreach($array1 as $value) {
            if (!in_array($value, $array2)) {
                array_push($result, $value);
            }
        }
        return $result;
    }

    public function updateEmployeeDetails(User $user, $previousSpecializations, $newSpecializations, $name) {
        $user->name = $name;
        $user->save();
        $employee = $user->employee;

        $toRemove = $this->arrayDifference($previousSpecializations, $newSpecializations);
        $toAdd = $this->arrayDifference($newSpecializations, $previousSpecializations);

        foreach($toRemove as $value) {
            $faultType = FaultType::where('name', '=', $value)->first();
            $employee->faultTypes()->detach($faultType);
        }

        foreach($toAdd as $value) {
            $faultType = FaultType::where('name', '=', $value)->first();
            $employee->faultTypes()->save($faultType);
        }

        $employee->save();
    }


    public function getUserSettings(User $user) {
        $customer = $user->customer;
        return $customer;
    }

    public function getEmployeeSettings(User $user) {
        $employee = $user->employee;
        return $employee;
    }

}