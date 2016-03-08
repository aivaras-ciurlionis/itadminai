<?php 

use app\repositories\FaultRepository;


class FaultsController extends BaseController {

    protected $fault;
    protected $rules = array('title' => 'required|max:50', 'type' => 'required', 'os' => 'required|max:50', 'description' => 'required|max:512');

    public function __construct(FaultRepository $fault) {
        $this->fault = $fault;
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    public function getNewFault() {
        return View::make('faults.new', ['$errors' => null]);
    }

    public function getAllFaults() {
        $sortField = Input::get("sortField");
        $sortDirection = Input::get("sortDirection");
        $search = Input::get("search");
        $faults;
        if (isset($sortField) || isset($search)) {
            if (!isset($sortDirection)) {
                $sortDirection = 'ASC';
            }
            $faults = $this->fault->getAllCustomerFaultsQuery(Auth::user()->customer, $sortField, $sortDirection, $search)->paginate(4);
        } else {
            $faults = $this->fault->getAllCustomerFaults(Auth::user()->customer)->paginate(4);
        }




        return View::make('faults.list', ['faults' => $faults, 'sortField' => $sortField, 
        'sortDirection' => $sortDirection, 'search' => $search]);
    }

    public function createNewFault() {
        $validator = Validator::make(Input::all(), $this->rules);
        if ($validator->passes()) {
            $this->fault->createNewFault(Auth::user()->customer, Input::get('title'),
            Input::get('description'), Input::get('os'), Input::get('type'));
            return Redirect::to('customer');
        } else {
            return Redirect::to('newfault')->withErrors($validator)->withInput();
        }

    }


}