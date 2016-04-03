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

Route::get('/homepage', array ('before' => 'auth', 'uses' => 'CustomerDataController@home'));
Route::get('/customer/settings', array ('before' => 'customer', 'uses' => 'CustomerDataController@getSettings'));
Route::post('/customer/settings', array ('before' => 'customer', 'uses' => 'CustomerDataController@postSettings'));


Route::get('/employee/settings', array ('before' => 'employee', 'uses' => 'CustomerDataController@getEmployeeSettings'));
Route::post('/employee/settings', array ('before' => 'employee', 'uses' => 'CustomerDataController@postEmployeeSettings'));



Route::get('/newfault', array('before' => 'customer', 'uses' => 'FaultsController@getNewFault'));
Route::get('/faults/{type}', array('before' => 'auth', 'uses' => 'FaultsController@getAllFaults'));
Route::post('/savefault', array('before' => 'customer', 'uses' => 'FaultsController@createNewFault'));
Route::post('/updateFault/{id}', array('before' => 'employee', 'uses' => 'FaultsController@updateFault'));
Route::get('/faults/details/{id}', array('before' => 'auth', 'uses' => 'FaultsController@faultDetails'));

Route::get('/faults/delete/{id}', array('before' => 'customerAdmin', 'uses' => 'FaultsController@deleteFault'));
Route::get('/faults/reopen/{id}', array('before' => 'customer', 'uses' => 'FaultsController@reopenFault'));
Route::post('/faults/setUser/{id}', array('before' => 'admin', 'uses' => 'FaultsController@setUser'));


Route::get('/users/customers', array('before' => 'admin', 'uses' => 'UsersController@getCustomers'));
Route::get('/users/employees', array('before' => 'admin', 'uses' => 'UsersController@getEmployees'));
Route::get('/users/details/{id}', array('before' => 'admin', 'uses' => 'UsersController@details'));
Route::get('/users/new', array('before' => 'admin', 'uses' => 'UsersController@newUser'));
Route::post('/users/saveUser', array('before' => 'admin', 'uses' => 'UsersController@saveUser'));

Route::get('/faultTypes', array('before' => 'admin', 'uses' => 'FaultsController@getTypes'));

Route::post('/saveType', array('before' => 'admin', 'uses' => 'FaultsController@saveType'));


Route::get('/users/enableAsignment/{id}', array('before' => 'admin', 'uses' => 'UsersController@enableAsignment'));
Route::get('/users/disableAsignment/{id}', array('before' => 'admin', 'uses' => 'UsersController@disableAsignment'));

Route::get('/users/setPassword/{id}', array('before' => 'admin', 'uses' => 'UsersController@setPassword'));
Route::get('/users/disableUser/{id}', array('before' => 'admin', 'uses' => 'UsersController@disableUser'));
Route::get('/users/enableUser/{id}', array('before' => 'admin', 'uses' => 'UsersController@enableUser'));
Route::post('/users/savePassword/{id}', array('before' => 'admin', 'uses' => 'UsersController@saveNewPassword'));