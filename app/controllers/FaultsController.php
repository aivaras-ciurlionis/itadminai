<?php 

use app\repositories\FaultRepository;
use app\repositories\UsersRepository;

class FaultsController extends BaseController {

    protected $fault;
    protected $users;
    protected $rules = array('title' => 'required|max:50', 'type' => 'required', 'os' => 'required|max:50', 'description' => 'required|max:512');

    protected $typeRule = array('typeName' => 'required|max:50');
    protected $typeMessages = array(
        'typeName.required' => 'Įveskite tipo pavadinimą',
        'typeName.max' => 'Tipo pavadinimas negali būti ilgesnis nei 50 simbolių'       
    );



    protected $messages = array('title.required' => 'Įveskite pavadinimą', 'title.max' => 'Pavadinimas negali būti ilgesnis nei :max simbolių', 'type.required' => 'Pasirinkite tipą', 'os.required' => 'Įveskite operacinę sistemą', 'os.max' => 'Operacinės sistemos pavadinimas negali būti ilgesnis nei :max simbolių', 'description.required' => 'Aprašykite gedimą', 'description.max' => 'Gedimo aprašymas negali būti ilgesnis nei :max simbolių', );

    public function __construct(FaultRepository $fault, UsersRepository $user) {
        $this->fault = $fault;
        $this->users = $user;
        $this->beforeFilter('csrf', array('on' => 'post'));
    }    
    
    public function getTypes(){
        $faultTypes = $this->fault->getAllFaultTypes();
        return View::make('faults.types', ['types' => $faultTypes]);
    }   
    
     public function saveType(){
        $validator = Validator::make(Input::all(), $this->typeRule, $this->typeMessages);
        if ($validator->passes()) {
            $this->fault->createNewType(Input::get('typeName'));
            return Redirect::to('faultTypes');
        } else {
            return Redirect::to('faultTypes')->withErrors($validator)->withInput();
        }              
    }     

    public function getNewFault() {
        $faultTypes = $this->fault->getAllFaultTypes();
        return View::make('faults.new', ['$errors' => null, 'faultTypes' => $faultTypes]);
    }

    public function faultDetails($id) {
        $backUrl = Input::get("backlist");
        $fault = $this->fault->getSingleFault($id, Auth::user());
        

        if ($fault === false) {
            return Redirect::to('login');
        }
        
        if (userHasRole(Auth::user()->roles, 'SysAdmin')){    
                       
            $emails = $this->users->getAllEmployeesEmails();  
            return View::make('faults.details', ['fault' => $fault, 
            'back' => $backUrl, 'emails' => $emails]);     

        } else {
            return View::make('faults.details', ['fault' => $fault, 'back' => $backUrl]);
        }     

    }    
    
    public function setUser($id) {
        $user = Input::get("user");
        $result = $this->fault->setUserForFault($id, $user);

        if ($result === false) {
            return Redirect::to('login');
        }    
           
        Session::flash('successMessage', 'Priskirtas vartotojas pakeistas į '.$user);
        
        return Redirect::to('faults/details/'.$id.'?backlist=all');
    }  
    
     public function reopenFault($id) {
        $backUrl = Input::get("backlist");
        $result = $this->fault->reopenFault(Auth::user(), $id);

        if ($result === false) {
            return Redirect::to('login');
        }    
           
        Session::flash('successMessage', 'Gedimas atidarytas iš naujo.');
        
        return Redirect::to('faults/'.$backUrl);
    }
    
    public function deleteFault($id) {
        $backUrl = Input::get("backlist");
        $result = $this->fault->deleteFault(Auth::user(), $id);

        if ($result === false) {
            return Redirect::to('login');
        }   
            
        Session::flash('successMessage', 'Gedimas ištrintas');
        
        return Redirect::to('faults/'.$backUrl);
    }   

    public function updateFault($id) {
        $newStatus = Input::get("state");

        switch ($newStatus) {
            case 'Registruota':
                $newStatusEn = 'registered';
                break;
            case 'Taisoma':
                $newStatusEn = 'inProgress';
                break;
            case 'Sutvarkyta':
                $newStatusEn = 'fixed';
                break;            
        }


        if ($this->fault->updateFault(Auth::user(), $id, $newStatusEn)) {
            Session::flash('successMessage', 'Gedimo statusas pakeistas į '.$newStatus);
            return Redirect::to('faults/details/'.$id.'?backlist=asigned');
        } else {
            return Redirect::to('login');
        }
    }

    public function getAllFaults($type) {
        $sortField = Input::get("sortField");
        $sortDirection = Input::get("sortDirection");
        $search = Input::get("search");
        $userFilter = Input::get("userFilter");
        //dd($userFilter);
        if (isset($sortField) || isset($search) || isset($userFilter)) {
            if (!isset($sortDirection)) {
                $sortDirection = 'ASC';
            }
            $faults = $this->fault->getAllQueryFaultsFor(Auth::user(), $type, $sortField, $sortDirection, $search, $userFilter)->paginate(10);
        } else {
            $faults = $this->fault->getAllFaultsFor(Auth::user(), $type)->paginate(10);
        }
       
        
        if (userHasRole(Auth::user()->roles, 'SysAdmin')){           
            
            $emails = $this->users->getAllEmployeesEmails(); 
            
             return View::make('faults.list', ['faults' => $faults, 'sortField' => $sortField, 'sortDirection' => $sortDirection, 'search' => $search,
              'userFilter' => $userFilter, 'type' => $type, 'emails' => $emails]);            
            
        } else {
          return View::make('faults.list', ['faults' => $faults, 'sortField' => $sortField, 'sortDirection' => $sortDirection, 'search' => $search, 'type' => $type]);            
        }     
    }

    public function createNewFault() {
        $validator = Validator::make(Input::all(), $this->rules, $this->messages);
        if ($validator->passes()) {
            $this->fault->createNewFault(Auth::user()->customer, Input::get('title'),
            Input::get('description'), Input::get('os'), Input::get('type'));
            Session::flash('successMessage', 'Gedimas "'.Input::get('title').'" užregistruotas!');
            return Redirect::to('faults/created');
        } else {
            return Redirect::to('newfault')->withErrors($validator)->withInput();
        }
    }


}