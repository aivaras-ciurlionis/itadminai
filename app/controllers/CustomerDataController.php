<?php 

use app\repositories\CustomerRepository;
use app\repositories\FaultRepository;

class CustomerDataController extends BaseController {

    protected $customer;
    protected $fault;
    protected $rulesCustomer = array('name' => 'required|min:2|max:50',
     'city' => 'required|min:2|max:50', 
     'country' => 'required|min:2|max:50');
     
     
     protected $rulesEmployee = array('name' => 'required|min:2|max:50',
     'specializations' => 'required'); 

    protected $messages = array(
        'name.required' => 'Įveskite vardą',
        'name.max' => 'Vardas negali būti ilgesnis nei :max simbolių',
        'name.min' => 'Vardas negali būti trumpesnis nei :min simboliai',
        'city.required' => 'Įveskite miestą',
        'city.min' => 'Miestas negali būti trumpesnis nei :min simboliai',
        'city.max' => 'Miestas negali būti ilgesnis nei :min simbolių',
        'country.required' => 'Įveskite valstybę',
        'country.min' => 'Valstybės pavadinimas negali būti trumpesnis nei :min simboliai',
        'country.max' => 'Valstybės pavadinimas negali būti ilgesnis nei :min simbolių'
    );
    
    protected $messagesEmployee = array(
        'name.required' => 'Įveskite vardą',
        'name.max' => 'Vardas negali būti ilgesnis nei :max simbolių',
        'name.min' => 'Vardas negali būti trumpesnis nei :min simboliai',
        'specializations.required' => 'Pasirinkite bent vieną specializaciją'
    );

    public function __construct(CustomerRepository $customer, FaultRepository $fault) {
        $this->customer = $customer;
        $this->fault = $fault;
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    public function home() {
        return View::make('customerData.index');
    }

    public function getSettings() {

        if (!isset($errors)) {
            return View::make('customerData.settings', ['customer' => $this->customer->getUserSettings(Auth::user()), 'name' => Auth::user()->name]);
        } else{
             return View::make('customerData.settings');
        }

    }
    
     public function getEmployeeSettings() {

        if (!isset($errors)) {
            return View::make('customerData.employeeSettings', ['employeeFaults' => $this->fault->getAllFaultTypesForUser(Auth::user()), 'name' => Auth::user()->name, 'allFaultTypes' =>  $this->fault->getAllFaultTypes()]);
        } else{
             return View::make('customerData.employeeSettings');
        }

    }
    


    public function postSettings() {

        $validator = Validator::make(Input::all(), $this->rulesCustomer, $this->messages);
        if ($validator->passes()) {
            $this->customer->updateDetails(Auth::user(), Input::get('name'), Input::get('city'), Input::get('country'));
            Session::flash('successMessage', 'Nustatymų pakeitimai išsaugoti!');
            return Redirect::to('customer/settings');

        } else {
            return Redirect::to('customer/settings')->withErrors($validator)->withInput();
        }

    }
    
    public function postEmployeeSettings() {

        $validator = Validator::make(Input::all(), $this->rulesEmployee, $this->messagesEmployee );
        if ($validator->passes()) {
            $previousSpecializations = $this->fault->getAllFaultTypesForUser(Auth::user());
            $this->customer->updateEmployeeDetails(Auth::user(), $previousSpecializations, Input::get('specializations'),  Input::get('name'));
            Session::flash('successMessage', 'Nustatymų pakeitimai išsaugoti!');
            return Redirect::to('employee/settings');

        } else {
            return Redirect::to('employee/settings')->withErrors($validator)->withInput();
        }

    }


}