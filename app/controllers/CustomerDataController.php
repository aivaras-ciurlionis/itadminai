<?php 

use app\repositories\CustomerRepository;


class CustomerDataController extends BaseController {

    protected $customer;
    protected $rules = array('name' => 'required|alpha|min:2|max:50', 'city' => 'required|alpha|min:2|max:50', 'country' => 'required|alpha|min:2|max:50');

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

        $validator = Validator::make(Input::all(), $this->rules);
        if ($validator->passes()) {
            $this->customer->updateDetails(Auth::user(), Input::get('name'), Input::get('city'), Input::get('country'));
            Session::flash('successMessage', 'Changes saved!');
            return Redirect::to('customer/settings');

        } else {
            return Redirect::to('customer/settings')->withErrors($validator)->withInput();
        }

    }


}