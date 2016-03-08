<?php 

use app\repositories\RoleRepository;

class AuthController extends BaseController {

    protected $userRules = array('name' => 'required|alpha|min:2', 'email' => 'required|email|unique:users', 'password' => 'required|between:6,30|confirmed', 'password_confirmation' => 'required|between:6,30');
    protected $role;
    
    public function __construct(RoleRepository $role) {
        $this->role = $role;
        $this->beforeFilter('csrf', array('on' => 'post'));
    }


    public function register() {
        return View::make('authentication.register', ['$errors' => null]);
    }


    public function login() {
        return View::make('authentication.login', ['$errors' => null]);
    }
    
    public function logout(){        
       Auth::logout();
       return Redirect::to('login');
    }

    public function authenticateUser() {
        if (Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password')), Input::get('remember') )) {
            return Redirect::to('customer')->with('message', 'You are now logged in!');
        } else {
            return Redirect::to('login')->withErrors(['Your username/password combination was incorrect']);withInput();
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



}