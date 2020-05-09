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

Route::get('/', function () {
    return view('welcome');
});


Route::get('plan/create','SubscriptionController@createPlan');
Route::get('plan/list','SubscriptionController@listPlans');
Route::get('plan/{planid}','SubscriptionController@planDetail');
Route::get('plan/{planid}/activate','SubscriptionController@activatePlan');
Route::get('plan/{id}/delete','SubscriptionController@deletePlan');
Route::post('plan/{planid}/agreement/create','SubscriptionController@createAgreement')->name('create-agreement');
Route::get('plan/agreement/{planid}','SubscriptionController@getAgreement');


Route::get('excute-agreement/{success}','SubscriptionController@excuteAgreement');