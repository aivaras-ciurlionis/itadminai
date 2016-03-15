<?php 

use app\repositories\FaultRepository;


class FaultsController extends BaseController {

    protected $fault;
    protected $rules = array('title' => 'required|max:50', 'type' => 'required', 'os' => 'required|max:50', 'description' => 'required|max:512');
    
    protected $messages = array(
        'title.required' => 'Įveskite pavadinimą',
        'title.max' => 'Pavadinimas negali būti ilgesnis nei :max simbolių',
        'type.required' => 'Pasirinkite tipą',
        'os.required' => 'Įveskite operacinę sistemą',
        'os.max' => 'Operacinės sistemos pavadinimas negali būti ilgesnis nei :max simbolių',
        'description.required' =>  'Aprašykite gedimą',
        'description.max' => 'Gedimo aprašymas negali būti ilgesnis nei :max simbolių',
    );
    
    public function __construct(FaultRepository $fault) {
        $this->fault = $fault;
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    public function getNewFault() {
        return View::make('faults.new', ['$errors' => null]);
    }
    
    public function faultDetails($id){
        $backUrl = Input::get("backlist");
        $fault = $this->fault->getSingleFault($id, Auth::user());
        
        if($fault === false){
            return Redirect::to('login');
        }
        
        return View::make('faults.details', 
        ['fault' => $fault, 'back' => $backUrl]);       
        
    }
    
    public function updateFault($id){
        $newStatus = Input::get("state");
        if($this->fault->updateFault(Auth::user(), $id, $newStatus)){
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
        $stateFilter = Input::get("stateFilter");
        if (isset($sortField) || isset($search) || isset($stateFilter)) {
            if (!isset($sortDirection)) {
                $sortDirection = 'ASC';
            }
            $faults = $this->fault->getAllQueryFaultsFor(Auth::user(), $type, $sortField, $sortDirection, $search, $stateFilter)->paginate(10);
        } else {
            $faults = $this->fault->getAllFaultsFor(Auth::user(), $type)->paginate(10);
        }
        
        return View::make('faults.list', ['faults' => $faults, 'sortField' => $sortField, 
        'sortDirection' => $sortDirection, 'search' => $search, 'stateFilter' => $stateFilter, 'type' => $type]);
    }

    public function createNewFault() {
        $validator = Validator::make(Input::all(), $this->rules, $this->messages);
        if ($validator->passes()) {
            $this->fault->createNewFault(Auth::user()->customer, Input::get('title'),
            Input::get('description'), Input::get('os'), Input::get('type'));
            return Redirect::to('faults/created');
        } else {
            return Redirect::to('newfault')->withErrors($validator)->withInput();
        }
    }


}