<?php 

use app\repositories\CustomerRepository;


class CustomerDataController extends BaseController {

    protected $customer;
    protected $rules = array('name' => 'required|min:2|max:50', 'city' => 'required|min:2|max:50', 'country' => 'required|min:2|max:50');

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


    public function __construct(CustomerRepository $customer) {
        $this->customer = $customer;
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
    


    public function postSettings() {

        $validator = Validator::make(Input::all(), $this->rules, $this->messages);
        if ($validator->passes()) {
            $this->customer->updateDetails(Auth::user(), Input::get('name'), Input::get('city'), Input::get('country'));
            Session::flash('successMessage', 'Nustatymų pakeitimai išsaugoti!');
            return Redirect::to('customer/settings');

        } else {
            return Redirect::to('customer/settings')->withErrors($validator)->withInput();
        }

    }


}