<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('/register','API\AuthController@register');

Route::post('/login','API\AuthController@login');

//logout
Route::middleware('auth:api')->get('/logout', 'API\AuthController@logout');

// Route::middleware('auth:api')->get('/getdata','API\AuthController@getdata');


//Citizen register
Route::post('/Citizen','API\CitizenController@store');

//PHI register
Route::post('/PHI','API\PHIController@store');

//Citizen Dashboard
Route::middleware('auth:api')->get('/Citizen', 'API\CitizenController@index')->name('Citizen');

//PHI Dashboard
Route::middleware('auth:api')->get('/PHI', 'API\PHIController@index')->name('PHI');

//View Citizen
Route::middleware('auth:api')->get('/Citizen/{id}/', 'API\CitizenController@show');

//Report Register Blade
Route::middleware('auth:api')->get('/Report{id}', 'API\ReportController@create');

//Report post
Route::middleware('auth:api')->post('/Report',['uses'=>'API\ReportController@store','as'=>'Report']);

//View Report
Route::middleware('auth:api')->get('/Report/{id}/', 'API\ReportController@show');

//Edit citizen profile blade
Route::middleware('auth:api')->get('/Editcitizen{id}', 'API\CitizenController@showcitizenprofile');

//Edit citizen profile
Route::middleware('auth:api')->put('/EditCitizen','API\CitizenController@editcitizenprofile');

//Edit citizen Location
Route::middleware('auth:api')->get('/LocCitizen{id}', 'API\CitizenController@showloc');

//Update Location
Route::middleware('auth:api')->put('/Citizens/{id}','API\CitizenController@update');

//Edit PHI profile blade
Route::middleware('auth:api')->get('/PHI{id}', 'API\PHIController@show');

//Edit citizen profile
Route::middleware('auth:api')->put('/PHIS','API\PHIController@update');

//Get citizen contacts
Route::middleware('auth:api')->get('/Citizens/{id}/contacts', 'API\CitizenController@contacts');


// Route::get('QRp', function () {
//      return QrCode::size(500)->backgroundColor(255,255,255)->generate('');
// });

//View Past Locations
Route::middleware('auth:api')->get('/Location/{id}', 'API\CitizenLocationController@show');

//QR post
Route::post('/QR',['uses'=>'API\QRController@store','as'=>'QR']);

//View QR
Route::get('/QR/{id}', 'API\QRController@show');

//Scan QR
Route::middleware('auth:api')->get('/ScanQR{id}', 'API\QRController@update');

//Search Bar
Route::middleware('auth:api')->post('/Search',['uses'=>'API\PHIController@search','as'=>'Search']);

//Citizen Health
Route::middleware('auth:api')->get('/Positive/{id}', 'API\CitizenController@positive');

//Citizen Health
Route::middleware('auth:api')->get('/Negative/{id}', 'API\CitizenController@negative');

//Citizen Health
Route::middleware('auth:api')->get('/Recovered/{id}', 'API\CitizenController@recovered');

//Delete citizen
Route::middleware('auth:api')->delete('/Citizens/{id}', 'API\CitizenController@destroy');
