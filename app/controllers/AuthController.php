<?php 

use app\repositories\RoleRepository;
use app\repositories\FaultRepository;


class AuthController extends BaseController {

    protected $userRules = array('name' => 'required|alpha|min:2', 'email' => 'required|email|unique:users', 'password' => 'required|between:6,30|confirmed', 'password_confirmation' => 'required|between:6,30');
    protected $role;
    protected $fault;

    public function __construct(RoleRepository $role, FaultRepository $fault) {
        $this->role = $role;
        $this->fault = $fault;
        $this->beforeFilter('csrf', array('on' => 'post'));
    }


    public function register() {
        return View::make('authentication.register', ['$errors' => null]);
    }

    public function registerEmployee() {
        $faultTypes = $this->fault->getAllFaultTypes();
        return View::make('authentication.registerEmployee', ['$errors' => null, 'faultTypes' => $faultTypes]);
    }


    public function login() {
        return View::make('authentication.login', ['$errors' => null]);
    }

    public function logout() {
        Auth::logout();
        return Redirect::to('login');
    }

    public function authenticateUser() {
        if (Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password')), Input::get('remember'))) {
            return Redirect::to('homepage')->with('message', 'Sėkmingai prisijungta!');
        } else {
            return Redirect::to('login')->withErrors(['El. paštas arba slaptažodis neteisingi.']);
            withInput();
        }
    }

    public function storeUser() {

        $validator = Validator::make(Input::all(), $this->userRules);

        if ($validator->passes()) {
            $user = new User;
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->password = Hash::make(Input::get('password'));
            $user->save();
            $this->role->addToRole($user, 'Customer');
            return Redirect::to('login');
        } else {
            return Redirect::to('register')->withErrors($validator)->withInput();
        }
    }

    public function storeEmployee() {
        $validator = Validator::make(Input::all(), $this->userRules);
        $specializationSet = false;
        if (Input::get('specializations') === null) {
            Session::flash('errorMessage', 'Pasirinkite bent vieną specializaciją.');
        } else {
            $specializationSet = true;
        }

        if ($validator->passes() && $specializationSet) {
            $user = new User;
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->password = Hash::make(Input::get('password'));

            $user->save();
            $this->role->addToRole($user, 'Employee');
            $this->role->saveSpecializations($user->employee, Input::get('specializations'));
            return Redirect::to('login');
        } else {
            return Redirect::to('registerEmployee')->withErrors($validator)->withInput();
        }
    }




}