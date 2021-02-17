<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/supplier', 'SupplierController@index')->name('supplier');
Route::get('/supplier/create', 'SupplierController@create');
Route::post('/supplier', 'SupplierController@store');
Route::get('/supplier/{id}', 'SupplierController@show');
Route::put('/supplier/{id}', 'SupplierController@update');
Route::delete('/supplier/{id}', 'SupplierController@destroy');
Route::post('/supplier/search', 'SupplierController@search');
Route::get('/supplier/get_employee/{id}', 'SupplierController@employee');

Route::get('/designation', 'DesignationController@index')->name('designation');
Route::get('/designation/create', 'DesignationController@create');
Route::post('/designation', 'DesignationController@store');
Route::get('/designation/{id}', 'DesignationController@show');
Route::put('/designation/{id}', 'DesignationController@update');
Route::delete('/designation/{id}', 'DesignationController@destroy');
Route::post('/designation/search', 'DesignationController@search');

Route::get('/employee', 'EmployeeController@index')->name('employee');
Route::get('/employee/create', 'EmployeeController@create');
Route::post('/employee', 'EmployeeController@store');
Route::get('/employee/{id}', 'EmployeeController@show');
Route::put('/employee/{id}', 'EmployeeController@update');
Route::delete('/employee/{id}', 'EmployeeController@destroy');
Route::post('/employee/search', 'EmployeeController@search');

Route::get('/contract', 'ContractController@index')->name('contract');
Route::get('/contract/create', 'ContractController@create');
Route::post('/contract', 'ContractController@store');
Route::get('/contract/{id}', 'ContractController@show');
Route::put('/contract/{id}', 'ContractController@update');
Route::delete('/contract/{id}', 'ContractController@destroy');
Route::post('/contract/search', 'ContractController@search');

Route::get('/project', 'ProjectController@index')->name('project');
Route::get('/project/create', 'ProjectController@create');
Route::post('/project', 'ProjectController@store');
Route::get('/project/{id}', 'ProjectController@show');
Route::put('/project/{id}', 'ProjectController@update');
Route::delete('/project/{id}', 'ProjectController@destroy');
Route::post('/project/search', 'ProjectController@search');

Route::get('/agreement', 'AgreementController@index')->name('agreement');
Route::get('/agreement/create', 'AgreementController@create');
Route::post('/agreement', 'AgreementController@store');
Route::get('/agreement/{id}', 'AgreementController@show');
Route::put('/agreement/{id}', 'AgreementController@update');
Route::delete('/agreement/{id}', 'AgreementController@destroy');
Route::post('/agreement/search', 'AgreementController@search');

Route::get('/agreement/get_renewal/{id}', 'AgreementController@get_renewal');
Route::post('/agreement/get_renewal', 'AgreementController@store_renewal');

Route::get('/rate', 'RateController@index')->name('rate');
Route::get('/rate/create', 'RateController@create');
Route::post('/rate', 'RateController@store');
Route::get('/rate/{id}', 'RateController@show');
Route::put('/rate/{id}', 'RateController@update');
Route::delete('/rate/{id}', 'RateController@destroy');
Route::post('/rate/search', 'RateController@search');

////////////////////////////////////////////// end of master ////////////////////////////////////////////


Route::get('/attendance', 'AttendanceController@index')->name('attendance');
Route::get('/attendance/create', 'AttendanceController@create');
Route::post('/attendance', 'AttendanceController@store');
Route::get('/attendance/{id}', 'AttendanceController@show');
Route::put('/attendance/{id}', 'AttendanceController@update');
Route::delete('/attendance/{id}', 'AttendanceController@destroy');
Route::post('/attendance/search', 'AttendanceController@search');



/////////////////////////////////// reports ///////////////////////////////////////////////////////////////

Route::get('/security_report', 'SecurityReportController@index')->name('security_report');
Route::get('/security_report/daysheet_emp_wise', 'SecurityReportController@daysheet_emp_wise');
Route::get('/security_report/monthly_advance_attendance', 'SecurityReportController@monthly_advance_attendance');
