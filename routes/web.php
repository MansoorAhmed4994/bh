<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
Auth::routes();
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


    //Route::get('/login', 'Auth\Admin\LoginController@showLoginForm')->name('admin.login');
    //Route::post('login', 'Auth\Admin\LoginController@login');
    // Route::post('logout', 'Auth\Admin\LoginController@logout')->name('admin.logout');

Route::group(['prefix' => 'admin','namespace' => 'Auth\Admin','as'=> 'admin.'],function(){

    //Route::get('/admin/login', ['LoginController', 'showLoginForm'])->name('admin.login');
    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');
});
Route::group(['middleware' => ['auth' => 'admin'] , 'prefix' => 'admin/','namespace' => 'Auth\Admin','as'=> 'admin.'],function(){

    // Route::resource('user/', 'UserController')->except('edit');
    Route::resource('user', 'UserController');
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
});
//Route::get('admin/user/{id}/edit/', 'Auth\Admin\UserController@edit')->name('admin.user.edit');

// Route::group(['prefix' => 'admin','namespace' => 'Auth\Admin','as'=> 'admin.'],function(){

//     Route::get('dashboard', 'DashboardController@index')->name('dashboard'); 
// });
Route::group(['middleware' => ['auth' => 'users'] ],function(){

    Route::get('/', 'HomeController@index')->name('dashboard');
    Route::get('user/dashboard', 'Auth\DashboardController@index')->name('user.dashboard');
});

Route::group(['prefix' => 'client/orders/', 'namespace' => 'Client\Orders', 'middleware' => 'auth:user,admin'],function(){

    
    
    Route::resource('ManualOrders', 'ManualOrdersController')->except('show');
    Route::get('ManualOrders/show/{id}', 'ManualOrdersController@show')->name('ManualOrders.show'); 
    Route::post('ManualOrders/delete-image', 'ManualOrdersController@delete_order_image')->name('ManualOrders.delete.order.image');
    Route::post('ManualOrders/add-image', 'ManualOrdersController@add_order_image')->name('ManualOrders.add.order.image');
    Route::post('ManualOrders/search-order', 'ManualOrdersController@search_order')->name('ManualOrders.search.order');
    Route::get('ManualOrders/search-order', 'ManualOrdersController@search_order');
    Route::get('ManualOrders/order-status/{status}/{ManualOrder}', 'ManualOrdersController@order_status')->name('ManualOrders.order.status');
    Route::get('ManualOrders/print/order-status/{ManualOrder}', 'ManualOrdersController@print_order_slip')->name('ManualOrders.print.order.slip');
    Route::post('ManualOrders/print/order-action', 'ManualOrdersController@order_action')->name('ManualOrders.order.action'); 
    Route::get('ManualOrders/status/order-list/{status}', 'ManualOrdersController@status_order_list')->name('ManualOrders.status.order.list'); 
    Route::post('ManualOrders/previouse/order-history', 'ManualOrdersController@previouse_order_history')->name('ManualOrders.previouse.order.history');
    Route::get('ManualOrders/dispatch-bulk-orders', 'ManualOrdersController@dispatch_bulk_orders')->name('ManualOrders.dipatch.bulk.orders');
    Route::get('ManualOrders/get-order-details/{ManualOrder}', 'ManualOrdersController@get_order_details')->name('ManualOrders.get.order.detail');
    Route::get('ManualOrders/dispatch-order-edit/{ManualOrder}', 'ManualOrdersController@popup_dispatch_edit')->name('ManualOrders.dispatch.order.edit');
    Route::post('ManualOrders/dispatch-order-edit/{ManualOrder}', 'ManualOrdersController@popup_dispatch_update')->name('ManualOrders.dispatch.order.update');
    Route::get('ManualOrders/testing', 'ManualOrdersController@testing')->name('ManualOrders.testing');
    
    
    
    //Route::get('create', 'ManualOrdersController@index')->name('client.manual.orders');
});

Route::group(['prefix' => 'mnp/', 'namespace' => 'Client\Orders', 'middleware' => 'auth:user,admin','as'=> 'mnp.'],function(){
    
    Route::post('create-booking', 'ManualOrdersController@mnp_bookings_store')->name('create.booking');

    
}); 

Route::group(['prefix' => 'trax/', 'namespace' => 'Shipment', 'middleware' => 'auth:user,admin','as'=> 'trax.'],function(){
    
    Route::post('create-booking', 'TraxController@CreateBulkBookingStore')->name('create.booking');
    Route::post('bookings', 'TraxController@CreateBulkBookingByOrderIds')->name('create.bulk.booking');
    Route::get('shipment/', function () {return view('client.orders.manual-orders.trax.create-bulk-booking-by-scan');})->name('create.bulk.booking.by.scan');
});  




Route::group(['prefix' => 'frontend/client/orders/', 'namespace' => 'Frontend\Client\Orders','as'=> 'Frontend.'],function(){

    Route::resource('ManualOrders', 'ManualOrdersController');
    Route::get('ManualOrders/client/authentication/', 'ManualOrdersController@authentication')->name('ManualOrders.authentication');
    Route::post('ManualOrders/client/authentication/', 'ManualOrdersController@verify_authentication')->name('ManualOrders.create.guest.cookie');
    //Route::post('ManualOrders/client/authentication/', 'ManualOrdersController@verify_authentication')->name('ManualOrders.create.guest.cookie');
    //Route::get('ManualOrders/show/{id}', 'ManualOrdersController@show')->name('ManualOrders.show');
    
});

    Route::get('click-here-to-confirm-your-order/{id}', 'Frontend\Client\Orders\ManualOrdersController@customer_order_confirmation')->name('ManualOrders.confirm.order.by.customer.show');
    Route::post('customer-order-confirmed/{ManualOrder}', 'Frontend\Client\Orders\ManualOrdersController@customer_order_confirmed')->name('ManualOrders.confirm.order.by.customer');



   
    Route::group(['prefix' => 'riders','as'=> 'riders.'],function(){
        Route::get('/login', 'RidersController@showLoginForm')->name('login');
        Route::post('login', 'RidersController@login');
        Route::post('logout', 'RidersController@logout')->name('logout');

    });

    

    Route::group(['prefix' => 'riders', 'as'=> 'riders.'],function(){

    Route::resource('/', 'RidersController')->except('show')->middleware('auth:admin');
    
    Route::get('/dashboard', 'RidersController@dashboard')->name('dashboard')->middleware('auth:rider');
    
    Route::get('/list', 'RidersController@list')->name('list')->middleware('auth:user,admin');

    Route::post('/generate-loadsheet', 'LoadSheetController@generate_load_sheet')->name('generate.load.sheet');
    //Route::get('ManualOrders/show/{id}', 'ManualOrdersController@show')->name('ManualOrders.show');
    
});
// Route::get('/home', 'Auth\Admin\LoginController@showLoginForm@index')->name('home');
//Route::get('/admin', 'Auth\User\LoginController@index')->name('admin.login'); 
