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

Route::get('/customer', array ('before' => 'customer', 'uses' => 'CustomerDataController@home'));
Route::get('/customer/settings', array ('before' => 'customer', 'uses' => 'CustomerDataController@getSettings'));
Route::post('/customer/settings', array ('before' => 'customer', 'uses' => 'CustomerDataController@postSettings'));

Route::get('/newfault', array('before' => 'customer', 'uses' => 'FaultsController@getNewFault'));
Route::get('/customer/faults', array('before' => 'customer', 'uses' => 'FaultsController@getAllFaults'));
Route::post('/savefault', array('before' => 'customer', 'uses' => 'FaultsController@createNewFault'));