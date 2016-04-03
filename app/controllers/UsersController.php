<?php 

use app\repositories\UsersRepository;
use app\repositories\RoleRepository;
use \User as User;

class UsersController extends BaseController {

    protected $users;

    protected $passwordRules = array(
        'password' => 'required|between:6,30|confirmed',
        'password_confirmation' => 'required|between:6,30'                
    );
         
    protected $userRules = array(
        'password' => 'required|between:6,30|confirmed',
        'password_confirmation' => 'required|between:6,30', 
        'name' => 'required|min:2|max:50',
        'email' => 'required|email|unique:users'      
    );       

    protected $userMessages = array(
        'email.required' => 'Įveskite el. paštą',
        'email.email' => 'Neatpažįstamas el. pašto formatas',       
        'email.unique' => 'Toks el. pašto adresas jau panaudotas',
        'name.required' => 'Įveskite vardą',
        'name.max' => 'Vardas negali būti ilgesnis nei :max simbolių',
        'name.min' => 'Vardas negali būti trumpesnis nei :min simboliai',
        'password.required' => 'Įveskite slaptažodį',
        'password.between' => 'Slaptažodis turi būti nuo 6 iki 30 simbolių',
        'password.confirmed' => 'Slaptažodžiai turi sutapti',
        'password_confirmation.required' => 'Pakartokite slaptažodį',
        'password_confirmation.between' => 'Pakartotas slaptažodis turi būti nuo 6 iki 30 simbolių',
    );     
         
    protected $passwordMessages = array(
        'password.required' => 'Įveskite slaptažodį',
        'password.between' => 'Slaptažodis turi būti nuo 6 iki 30 simbolių',
        'password.confirmed' => 'Slaptažodžiai turi sutapti',
        'password_confirmation.required' => 'Pakartokite slaptažodį',
        'password_confirmation.between' => 'Pakartotas slaptažodis turi būti nuo 6 iki 30 simbolių',
    );

    public function __construct(UsersRepository $user, RoleRepository $role) {
        $this->users = $user;
        $this->role = $role;
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
    
    public function enableAsignment($id)
    {
        $backlist = Input::get('backlist');
        $this->users->enableAsignment($id);
        Session::flash('successMessage', 'Vartotojui gedimai vėl priskiriami!');
        return Redirect::to('users/'.$backlist);
    }   
    
    public function disableAsignment($id)
    {
        $backlist = Input::get('backlist');
        $this->users->disableAsignment($id);
        Session::flash('successMessage', 'Vartotojas gedimai nebebus priskiriami!');
        return Redirect::to('users/'.$backlist);
    }   
    
    public function details($id)
    {
        $backlist = Input::get('backlist');
        $thisUser = User::find($id);       
        $employeeId =  $thisUser->employee->id;
        $userDetails = $this->users->getSingleEmployee($employeeId);
        $employeeSpecializations = $this->users->getEmployeeSpecializations($employeeId);      

        return View::make('users.details', ['user' => $userDetails, 'backlist' => $backlist, 'employeeSpecializations' => $employeeSpecializations]);
    }    
    
    public function setPassword($id)
    {
        $backlist = Input::get('backlist');
        $thisUser = User::find($id);        
        return View::make('users.setPassword', ['user' => $thisUser, 'backlist' => $backlist]);
    }
    
    
    public function newUser()
    {
        return View::make('users.new');
    }
    
    public function toRoleName($name)
    {
        switch ($name) {
            case 'Vartotojas':
                return 'Customer';
            case 'Darbuotojas':
                return 'Employee';
            case 'Sistemos administratorius':
                return 'SysAdmin';        
        }        
    }   
    
    public function saveUser()
    {        
        $validator = Validator::make(Input::all(), $this->userRules, $this->userMessages);
        
        if ($validator->passes()) {
            $user = $this->users->createUser(Input::get("name"), Input::get("email"), Input::get("password"));
            $this->role->addToRole($user, $this->toRoleName(Input::get("role")));           
            Session::flash('successMessage', 'Vartotojas '.Input::get("email").' sukurtas!');
            return Redirect::to('users/new');
        } else {
             return Redirect::to('users/new')->withErrors($validator)->withInput();
        }        

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