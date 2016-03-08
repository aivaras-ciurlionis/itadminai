<?php

namespace app\repositories;

use app\models\Customer;
use User;

class CustomerRepository
{
    public function updateDetails(User $user, $name, $city, $country)
    {
        $user->name = $name;
        $user->save();
        $customer = $user->customer;
        $customer->city = $city;
        $customer->country = $country;        
        $customer->save();       
    } 
    
    public function getUserSettings(User $user)
    {
        $customer = $user->customer; 
        return $customer;
    } 
    
}