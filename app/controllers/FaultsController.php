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
        
        $fault = $this->fault->getSingleFault($id, Auth::user()->customer);
        
        if($fault === false){
            return Redirect::to('login');
        }
        
        return View::make('faults.details', 
        ['fault' => $fault]);       
        
    }

    
    public function getAllFaults() {
        $sortField = Input::get("sortField");
        $sortDirection = Input::get("sortDirection");
        $search = Input::get("search");
        $stateFilter = Input::get("stateFilter");
        $faults;
        if (isset($sortField) || isset($search) || isset($stateFilter)) {
            if (!isset($sortDirection)) {
                $sortDirection = 'ASC';
            }
            $faults = $this->fault->getAllCustomerFaultsQuery(Auth::user()->customer, $sortField, $sortDirection, $search, $stateFilter)->paginate(4);
        } else {
            $faults = $this->fault->getAllCustomerFaults(Auth::user()->customer)->paginate(4);
        }
        
        return View::make('faults.list', ['faults' => $faults, 'sortField' => $sortField, 
        'sortDirection' => $sortDirection, 'search' => $search, 'stateFilter' => $stateFilter]);
    }

    public function createNewFault() {
        $validator = Validator::make(Input::all(), $this->rules, $this->messages);
        if ($validator->passes()) {
            $this->fault->createNewFault(Auth::user()->customer, Input::get('title'),
            Input::get('description'), Input::get('os'), Input::get('type'));
            return Redirect::to('customer');
        } else {
            return Redirect::to('newfault')->withErrors($validator)->withInput();
        }

    }


}