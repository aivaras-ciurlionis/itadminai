<?php 

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/



Route::get('/', function() {
    return View::make('authentication.landing');
});

Route::get('/login', 'AuthController@login');
Route::post('/login', 'AuthController@authenticateUser');
Route::get('/logout', 'AuthController@logout');
Route::get('/register', 'AuthController@register');
Route::post('/register', 'AuthController@storeUser');

Route::get('/registerEmployee', 'AuthController@registerEmployee');
Route::post('/registerEmployee', 'AuthController@storeEmployee');

Route::get('/customer', array ('before' => 'customerEmployee', 'uses' => 'CustomerDataController@home'));
Route::get('/customer/settings', array ('before' => 'customer', 'uses' => 'CustomerDataController@getSettings'));
Route::post('/customer/settings', array ('before' => 'customer', 'uses' => 'CustomerDataController@postSettings'));


Route::get('/employee/settings', array ('before' => 'employee', 'uses' => 'CustomerDataController@getEmployeeSettings'));
Route::post('/employee/settings', array ('before' => 'employee', 'uses' => 'CustomerDataController@postEmployeeSettings'));



Route::get('/newfault', array('before' => 'customer', 'uses' => 'FaultsController@getNewFault'));
Route::get('/faults/{type}', array('before' => 'customerEmployee', 'uses' => 'FaultsController@getAllFaults'));
Route::post('/savefault', array('before' => 'customer', 'uses' => 'FaultsController@createNewFault'));
Route::post('/updateFault/{id}', array('before' => 'employee', 'uses' => 'FaultsController@updateFault'));
Route::get('/faults/details/{id}', array('before' => 'customerEmployee', 'uses' => 'FaultsController@faultDetails'));

Route::get('/faults/delete/{id}', array('before' => 'customerAdmin', 'uses' => 'FaultsController@deleteFault'));
Route::get('/faults/reopen/{id}', array('before' => 'customer', 'uses' => 'FaultsController@reopenFault'));