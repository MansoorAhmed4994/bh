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

Route::group(['prefix' => 'client/orders/', 'namespace' => 'client\Orders', 'middleware' => 'auth:user'],function(){

    Route::resource('ManualOrders', 'ManualOrdersController');
    Route::post('ManualOrders/delete-image', 'ManualOrdersController@delete_order_image')->name('ManualOrders.delete.order.image');
    Route::post('ManualOrders/add-image', 'ManualOrdersController@add_order_image')->name('ManualOrders.add.order.image');
    //Route::get('create', 'ManualOrdersController@index')->name('client.manual.orders');
});

// Route::get('/home', 'Auth\Admin\LoginController@showLoginForm@index')->name('home');
//Route::get('/admin', 'Auth\User\LoginController@index')->name('admin.login'); 
