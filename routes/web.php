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
Route::get('/', 'HomeController@index')->name('home');

    //Route::get('/login', 'Auth\Admin\LoginController@showLoginForm')->name('admin.login');
    //Route::post('login', 'Auth\Admin\LoginController@login');
    // Route::post('logout', 'Auth\Admin\LoginController@logout')->name('admin.logout');

Route::group(['prefix' => 'admin','namespace' => 'Auth\Admin'],function(){

    //Route::get('/admin/login', ['LoginController', 'showLoginForm'])->name('admin.login');
    Route::get('/login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('admin.logout');
});

Route::group(['prefix' => 'admin','namespace' => 'Auth\Admin'],function(){

    Route::get('dashboard', 'DashboardController@index'); 
});

Route::group(['prefix' => 'user', 'namespace' => 'Auth' ,'as'=> 'user.'],function(){

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
});

Route::group(['prefix' => 'client/orders/', 'namespace' => 'Client\Orders', 'middleware' => 'auth:user'],function(){

    Route::resource('ManualOrders', 'ManualOrdersController')->except('show');
    Route::get('ManualOrders/show/{id}', 'ManualOrdersController@show')->name('ManualOrders.show');
    Route::post('ManualOrders/delete-image', 'ManualOrdersController@delete_order_image')->name('ManualOrders.delete.order.image');
    Route::post('ManualOrders/add-image', 'ManualOrdersController@add_order_image')->name('ManualOrders.add.order.image');
    Route::post('ManualOrders/search-order', 'ManualOrdersController@search_order')->name('ManualOrders.search.order');
    Route::get('ManualOrders/search-order', 'ManualOrdersController@search_order');
    Route::get('ManualOrders/order-status/{status}/{ManualOrder}', 'ManualOrdersController@order_status')->name('ManualOrders.order.status');
    Route::get('ManualOrders/print/order-status/{ManualOrder}', 'ManualOrdersController@print_order_slip')->name('ManualOrders.print.order.slip');
    Route::post('ManualOrders/print/order-action', 'ManualOrdersController@order_action')->name('ManualOrders.order.action');
    //Route::get('create', 'ManualOrdersController@index')->name('client.manual.orders');
});

Route::group(['prefix' => 'frontend/client/orders/', 'namespace' => 'Frontend\Client\Orders'],function(){

    Route::resource('ManualOrders', 'ManualOrdersController',['as' => 'Frontend'])->except('show');
    //Route::get('ManualOrders/show/{id}', 'ManualOrdersController@show')->name('ManualOrders.show');
    
});
// Route::get('/home', 'Auth\Admin\LoginController@showLoginForm@index')->name('home');
//Route::get('/admin', 'Auth\User\LoginController@index')->name('admin.login'); 
