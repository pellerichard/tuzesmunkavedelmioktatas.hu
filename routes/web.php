<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * @comment "Felhasználói csoportok"
*/
Route::get('/jelszo-valtoztatas','AccountController@passwordchange');
Route::get('/korlevel-tiltas/{token}','AccountController@korlevelTiltas');
Route::post('/felhasznalo/isLoggedIn','AccountController@isLoggedIn');
Route::get('/alkalmazott-ertesites/{token}', function() { return view('index'); });
Route::post('/send-employee-notification/{token}','AccountController@alkalmazottErtesites');

Route::post('/felhasznalo/validateSession','AccountController@validateSession');


Route::group(['middleware' => ['IsNotLoggedIn']], function () {
    Route::get('/bejelentkezes', function() { return view('index'); });
    Route::get('/elfelejtett-jelszo', function() { return view('index'); });
    Route::post('/felhasznalo/forgot-password','AccountController@forgotpassword');
    Route::post('/felhasznalo/login','AccountController@login');
    Route::resource('/felhasznalo', 'AccountController');
});

Route::group(['middleware' => ['IsUser']], function () {
    Route::get('/teszt', function() { return view('index'); });
    Route::get('/hogyan-mukodik-a-rendszer', function() { return view('index'); });
    Route::post('/felhasznalo/fetchQuestion','AccountController@fetchQuestion');
    Route::post('/felhasznalo/sendTest','AccountController@sendTest');
    Route::post('/felhasznalo/save-settings','AccountController@savesettings');
    Route::post('/felhasznalo/fetchCompanies','AccountController@fetchCompanies');
    Route::post('/felhasznalo/fetchCompany','AccountController@fetchCompany');
    Route::get('/felhasznaloi-csoportok/{id}', function() { return view('index'); });
    Route::resource('/felhasznaloi-csoportok', 'GroupController');
    Route::post('/felhasznaloi-csoportok/csoportok/{id}','AccountController@fetchCompanyGroups');
    Route::post('/felhasznalo/logout','AccountController@logout');
});

Route::group(['middleware' => ['IsCompanyOwner']], function () {
    Route::get('/', function() { return view('index'); });
    Route::post('/felhasznalo/cegeim/uj-munkatars','AccountController@addCompanyMember');
    Route::post('/felhasznalo/cegeim/torles-munkatars','AccountController@deleteCompanyMember');
    Route::post('/felhasznalo/cegeim/szerkesztes-munkatars','AccountController@editCompanyMember');
    Route::get('/cegeim', function() { return view('index'); });
    Route::get('/cegeim/{ref}', function() { return view('index'); });
    Route::get('/beallitasok', function() { return view('index'); });
});

Route::group(['middleware' => ['IsAdmin']], function () {
    Route::resource('/admin/ceg', 'Admin\CompanyController');
    Route::resource('/admin/teszt', 'Admin\TestController');
    Route::resource('/admin/testinput', 'Admin\TestInputController');
    Route::post('/admin/teszt/fetchTest','Admin\TestController@fetchTest');
    Route::post('/admin/teszt/fetchTests','Admin\TestController@fetchTests');

    Route::get('/admin/tesztek','Admin\TestController@index');
    Route::get('/admin/cegek', function() { return view('index'); });
    Route::get('/admin/felhasznalok', function() { return view('index'); });
    Route::get('/admin/beallitasok', function() { return view('index'); });
    Route::get('/admin/cegek/{ref}', function() { return view('index'); });
});