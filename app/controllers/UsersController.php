<?php 

use app\repositories\UsersRepository;
use \User as User;

class UsersController extends BaseController {

    protected $users;

    protected $passwordRules = array('password' => 'required|between:6,30|confirmed', 'password_confirmation' => 'required|between:6,30');
    protected $passwordMessages = array(
        'password.required' => 'Įveskite slaptažodį',
        'password.between' => 'Slaptažodis turi būti nuo 6 iki 30 simbolių',
        'password.confirmed' => 'Slaptažodžiai turi sutapti',
        'password_confirmation.required' => 'Pakartokite slaptažodį',
        'password_confirmation.between' => 'Pakartotas slaptažodis turi būti nuo 6 iki 30 simbolių',
    );

    public function __construct(UsersRepository $user) {
        $this->users = $user;
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    public function getCustomers() {
        $sortField = Input::get("sortField");
        $sortDirection = Input::get("sortDirection");
        $search = Input::get("search");

        if (isset($sortField) || isset($search)) {
            if (!isset($sortDirection)) {
                $sortDirection = 'ASC';
            }
            $userList = $this->users->getAllUsersQuery('customers', $sortField, $sortDirection, $search)->paginate(10);
        } else {
            $userList = $this->users->getAllUsers('customers')->paginate(10);
        }

        return View::make('users.customers', ['users' => $userList, 'sortField' => $sortField, 'sortDirection' => $sortDirection, 'search' => $search]);
    }
    
    public function getEmployees() {
        $sortField = Input::get("sortField");
        $sortDirection = Input::get("sortDirection");
        $search = Input::get("search");

        if (isset($sortField) || isset($search)) {
            if (!isset($sortDirection)) {
                $sortDirection = 'ASC';
            }
            $userList = $this->users->getAllUsersQuery('employees', $sortField, $sortDirection, $search)->paginate(10);
        } else {
            $userList = $this->users->getAllUsers('employees')->paginate(10);
        }

        return View::make('users.employees', ['users' => $userList, 'sortField' => $sortField, 'sortDirection' => $sortDirection, 'search' => $search]);
    }
    
    
    
    
    public function enableUser($id)
    {
        $backlist = Input::get('backlist');
        $this->users->enableUser($id);
        Session::flash('successMessage', 'Vartotojas atblokuotas!');
        return Redirect::to('users/'.$backlist);
    }   
    
    public function disableUser($id)
    {
        $backlist = Input::get('backlist');
        $this->users->disableUser($id);
        Session::flash('successMessage', 'Vartotojas užblokuotas!');
        return Redirect::to('users/'.$backlist);
    }     
    
    public function setPassword($id)
    {
        $backlist = Input::get('backlist');
        $thisUser = User::find($id);        
        return View::make('users.setPassword', ['user' => $thisUser, 'backlist' => $backlist]);
    }

    public function saveNewPassword($id) {
        $backlist = Input::get('backlist');
        $validator = Validator::make(Input::all(), $this->passwordRules, $this->passwordMessages);
        if ($validator->passes()) {
            $this->users->setNewPasswordForUser($id, Input::get("password"));
            Session::flash('successMessage', 'Slaptažodis pakeistas!');
              return Redirect::to('users/'.$backlist);
        } else {
             return Redirect::to('users/setPassword/'.$id.'?backlist='.$backlist)->withErrors($validator)->withInput();
        }
    }
}