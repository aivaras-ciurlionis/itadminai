<?php

namespace app\repositories;

use User;
use app\models\Role;
use app\models\Customer;



class RoleRepository
{
    public function addToRole(User $user, $roleName)
    {
        $role = Role::where('name', $roleName)->first();
        
        $user->role_id = $role->id;
        
        $user->save();
        
        if ($roleName === "Customer"){
            $this->createCustomer($user);
        }
        
    }
    
    public function createCustomer(User $user) 
    {
        $customer = Customer::create([
            'city' => '',
            'country' => ''                   
        ]);       
        
        $customer->user_id = $user->id;
        $customer->save();      
    }
    
    
}