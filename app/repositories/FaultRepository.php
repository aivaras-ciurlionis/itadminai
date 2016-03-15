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

    public function getAllCustomerFaults(Customer $customer) {
        return Fault::where('customer_id', $customer->id);
    }

    public function getSingleFault($id, Customer $customer) {
        $exists = false;
        $fault = $customer->faults->find($id);

        if (!$fault) {
            return false;
        }

        return $fault;

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