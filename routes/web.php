<?php

use Illuminate\Support\Facades\Route;
use App\Customer;
use App\Country;
use App\State;
use App\User;
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


Route::group(['middleware' => ['auth.shopify']], function () {
    Route::get('/', 'AdminController@dashboard')->name('home');

    Route::get('/customers', function ()
    {

        $customers = Customer::where('is_agent',1)->latest()->paginate(20);
        $countries = Country::all();
        return view('customers', compact('customers', 'countries'));
    })->name('customers');
    Route::get('/customer-view/{id}', 'CustomerController@customer_detail')->name('customer-view');
    Route::get('/customer-delete{id}', 'CustomerController@customer_delete')->name('customer-delete');

    Route::get('/sync-customer', 'CustomerController@ShopifyCustomers')->name('sync-customer');
    Route::post('/add-agent', 'CustomerController@addAgent')->name('add_agent');


    Route::get('/professionals', 'CustomerController@professionals')->name('professionals');
    Route::get('/professionals_check', 'CustomerController@professionals_check')->name('professionals.check');
    Route::get('/professional-view/{id}', 'CustomerController@professional_view')->name('professional-view');
    Route::get('/professional-detail/{id}', 'CustomerController@professional_detail')->name('professional-detail');
    Route::get('/professional/{id}/approve', 'CustomerController@professional_approve')->name('professional.approve');
    Route::get('/professional/{id}/disapprove', 'CustomerController@professional_disapprove')->name('professional.disapprove');


    Route::get('/orders', 'OrderController@showAll')->name('orders');
    Route::get('/sync-orders', 'OrderController@syncOrders')->name('sync_orders');
    Route::post('show-states', function (Request $request)
    {
        $input = Request::all();
        if(Request::isMethod('post') && Request::ajax()) {
            if($input['countryName']) {
                $country = Country::where('name', $input['countryName'])->first();

                $states = $country->states;
                $returnHTML = view('customer.state', ['states' => $states])->render();
                return response()->json( array('success' => true, 'html'=>$returnHTML) );
            }
        }
    })->name('show_states');
    Route::post('show-city', function (Request $request)
    {
        $input = Request::all();
        if(Request::isMethod('post') && Request::ajax()) {
            if($input['cityName']) {
                $states = State::where('name', $input['cityName'])->first();
                $cities = $states->cities;
                $returnHTML = view('customer.city', ['cities' => $cities])->render();
                return response()->json( array('success' => true, 'html'=>$returnHTML) );
            }
        }
    })->name('show_cities');

    Route::post('/refund', 'OrderController@makeRefund')->name('make_refund');
    Route::get('/analytics', 'AnalyticsController@showAnalytics')->name('analytics');


    //Agent
    Route::get('/agenthome', 'HomeController@index')->name('agenthome');

    Route::get('/agent-order-history', 'AgentController@index')->name('agent-order-history')->middleware('auth');
    Route::get('/agent-order-view/{id}', 'AgentController@order_detail')->name('agent-order-view')->middleware('auth');

    Route::get('/agent-stores', 'AgentController@agent_store')->name('agent-stores');
    Route::post('/agent-add-store', 'AgentController@agent_add_store')->name('agent-add-store');
    Route::get('/agent-delete-store/{id}', 'AgentController@agent_delete_store')->name('agent-delete-store');



});

Route::post('/check-customer-email', 'CustomerController@check_customer_email')->name('check-customer-email');
Route::get('/agent-dashboard', 'AgentController@agent_dashboard')->name('agent-dashboard');

Auth::routes();

Route::get('/professional/form', 'CustomerController@professional_form')->name('professionals.form');
Route::post('/professional/form', 'CustomerController@professional_form_submit')->name('professionals.form.submit');


Route::post('/sell-area', 'HomeController@index')->name('sell-area');


Route::get('logout', 'LoginController@logout');
