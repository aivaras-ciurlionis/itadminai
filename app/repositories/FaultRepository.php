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

    public function getAllCustomerFaults(Customer $customer) {
        return Fault::where('customer_id', $customer->id);
    }



    public function getAllCustomerFaultsQuery(Customer $customer, $field, $direction, $search) {
        $sortDirection = 'ASC';
        if ($direction === 'DESC') {
            $sortDirection = 'DESC';
        }

        $faults = Fault::where('customer_id', $customer->id);

        if (isset($search)) {
            $faults = $faults->where('title', 'like', $search);
        }

        if (isset($field)) {
            $faults = $faults->orderBy($field, $sortDirection);
        }

        return $faults;
    }


}