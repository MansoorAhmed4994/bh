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
/*==================================
            Global Routes
====================================*/
 
    // Route::get('/error','GlobalController@error')->name('global.error');
    // Route::view('/error', 'global.error')->name('global.error');previouse/order-history
    
/*==================================
            Customer Routes
====================================*/

Route::group(['prefix' => 'customer','as'=> 'customer.'],function(){
    
    Route::get('/create','Frontend\CustomersController@Create')->name('create');
    Route::post('/store','Frontend\CustomersController@store')->name('store');
    Route::post('/login','Frontend\CustomersController@GetCustomerId')->name('login');
    Route::post('/delete/coockies','Frontend\CustomersController@DeleteCustomerCoockies')->name('delete.coockies');
    Route::post('/upload/product/screenshot','Frontend\CustomersController@UploadProductScreentShot')->name('upload.product.screenshot');
    Route::post('/delete/product/screenshot','Frontend\CustomersController@DeleteOrderImage')->name('delete.product.screenshot');
    
});

Route::group(['prefix' => 'support', 'namespace' => 'Client','as'=> 'support.', 'middleware' => 'auth:user,admin'],function(){
    
    Route::get('/','SupportController@index')->name('index');
    Route::get('/create','SupportController@Create')->name('create');
    Route::post('/store','SupportController@store')->name('store');
    Route::get('/edit/{id}','SupportController@edit')->name('delete.product.screenshot');
    Route::post('/update','SupportController@GetCustomerId')->name('login');
    Route::post('/customer-details','SupportController@CustomerDetails')->name('customer.details');
    
    
});


/*==================================
            Admin Routes
====================================*/
Route::get('/test','Client\Orders\ManualOrdersController@test');


Route::post('test/fileupload', 'Client\Orders\ManualOrdersController@TestFileUpload')->name('TestFileUpload');

Route::group(['middleware' => 'auth:user'],function(){

    Route::get('/', 'HomeController@index')->name('dashboard');
    Route::get('/contactmanage', 'HomeController@contact')->name('contactmanage');  
    Route::get('user/dashboard', 'Auth\DashboardController@index')->name('user.dashboard');
    Route::post('user/dashboard', 'Auth\DashboardController@index')->name('user.dashboard.search');
    Route::post('user/dashboard/GetDailyCallingTeamDispatchData', 'Auth\DashboardController@GetDailyCallingTeamDispatchData')->name('user.dashboard.GetDailyCallingTeamDispatchData');
});



Route::group(['prefix' => 'client/orders/', 'namespace' => 'Client\Orders', 'middleware' => 'auth:user,admin'],function(){
     
    Route::any('ManualOrders/list', 'ManualOrdersController@index')->name('ManualOrders.index');
    Route::post('ManualOrders/search-order', 'ManualOrdersController@search_order')->name('ManualOrders.search.order');
    Route::get('ManualOrders/search-order', 'ManualOrdersController@search_order');
    Route::get('ManualOrders/order-status/{status}/{ManualOrder}', 'ManualOrdersController@order_status')->name('ManualOrders.order.status');
    Route::get('ManualOrders/print/order-status/{ManualOrder}', 'ManualOrdersController@print_order_slip')->name('ManualOrders.print.order.slip');
    Route::get('inactivecustomers', 'ManualOrdersController@InActiveCustomers')->name('inactive.customers');
    Route::get('ManualOrders/status/order-list/{status}', 'ManualOrdersController@status_order_list')->name('ManualOrders.status.order.list'); 
    Route::post('ManualOrders/previouse/order-history', 'ManualOrdersController@previouse_order_history')->name('ManualOrders.previouse.order.history');
    Route::post('customer/details', 'ManualOrdersController@CustomerDetailsByCode')->name('customer.details');
    Route::get('ManualOrders/dispatch-bulk-orders', 'ManualOrdersController@dispatch_bulk_orders')->name('ManualOrders.dipatch.bulk.orders');
    Route::get('ManualOrders/quick-search', 'ManualOrdersController@QuickSearch')->name('manualOrders.quick.search'); 
    Route::post('ManualOrders/quick-search', 'ManualOrdersController@QuickSearchActions')->name('manualOrders.quick.search.actions');  
    Route::get('get_product_details/{Sku}', 'ManualOrdersController@get_product_details')->name('get.product.details');
    Route::get('ManualOrders/print_slip_by_scan/', 'ManualOrdersController@print_slip_by_scan')->name('ManualOrders.print.slip.by.scan');
    Route::post('ManualOrders/print/order-action', 'ManualOrdersController@order_action')->name('ManualOrders.order.action'); 
    Route::get('ManualOrders/get-order-details/{ManualOrder}', 'ManualOrdersController@get_order_details')->name('ManualOrders.get.order.detail'); 
    Route::get('ManualOrders/print/PosSlip/{ManualOrder}', 'ManualOrdersController@PrintPosSlip')->name('ManualOrders.print.pos.slip'); 
    Route::get('ManualOrders/chec-pos-slip-duplication/{ManualOrder}', 'ManualOrdersController@CheckPosSlipDuplication')->name('ManualOrders.check.pos.slip.duplication'); 
    Route::post('ManualOrders/GetAdvacePayment', 'ManualOrdersController@GetAdvacePayment')->name('ManualOrders.get.advance.payment'); 
    
    
    //edit
    Route::get('ManualOrders/edit/{ManualOrder}/', 'ManualOrdersController@edit')->name('ManualOrders.edit');
    Route::get('ManualOrders/details/', 'ManualOrdersController@details')->name('ManualOrders.details');
    Route::any('ManualOrders/update/{ManualOrder}', 'ManualOrdersController@update')->name('ManualOrders.update');
    Route::post('ManualOrders/add-image', 'ManualOrdersController@add_order_image')->name('ManualOrders.add.order.image');
    Route::get('ManualOrders/QuickEditOrder/{ManualOrder}', 'ManualOrdersController@QuickEditOrder')->name('ManualOrders.quick.edit.order'); 
    Route::post('ManualOrders/QuickEditOrderUpdate/{ManualOrder}', 'ManualOrdersController@QuickEditOrderUpdate')->name('ManualOrders.update.quick.edit.order');
    Route::post('ManualOrders/ChangeOrderStatus/', 'ManualOrdersController@ChangeOrderStatus')->name('ManualOrders.change.status');
    Route::post('ManualOrders/assign-to/{id}/{assig_to}', 'ManualOrdersController@UpdateAssignTo')->name('ManualOrders.update.assign.to');
    
    //create
    Route::post('ManualOrders/store', 'ManualOrdersController@store')->name('ManualOrders.store');
    Route::get('ManualOrders/create', 'ManualOrdersController@create')->name('ManualOrders.create');
    Route::get('ManualOrders/show/{id}', 'ManualOrdersController@show')->name('ManualOrders.show'); 
    Route::post('generate/scan/slip', 'ManualOrdersController@print_slip_by_scan_store')->name('generate.scan.slip');
    
    
    Route::get('ManualOrders/shipment/cities/{shipmentcompany}', 'ManualOrdersController@GetShipmentCities')->name('ManualOrders.get.shipment.cities');
 
    //delete
    Route::post('ManualOrders/delete-image', 'ManualOrdersController@delete_order_image')->name('ManualOrders.delete.order.image');
    
    //testing
    // Route::get('testing123', 'Client\Orders\ManualOrdersController@testing')->name('ManualOrders.testing123');
    
    //Reports
    Route::any('ManualOrders/reports/printed slips', 'ManualOrdersController@PrintedSlipsReport')->name('ManualOrders.reports.printed.slips');
    
});

//admin mnp
Route::group(['prefix' => 'mnp/', 'namespace' => 'Shipment', 'middleware' => 'auth:user,admin','as'=> 'mnp.'],function(){
    
    Route::post('create-booking', 'MnpController@mnp_bookings_store')->name('create.booking');
    Route::get('shipment/', function () {return view('client.orders.manual-orders.mnp.create-bulk-booking-by-scan');})->name('create.bulk.booking.by.scan');
    Route::post('bookings', 'MnpController@MnpCreateBulkBookingByOrderIds')->name('create.bulk.booking');

}); 


/*==================================
            Customer Payments Routes
====================================*/
Route::group(['prefix' => 'client/orders/', 'namespace' => 'Client\Orders', 'middleware' => 'auth:user,admin'],function(){
    
    Route::any('CustomerPayments/', 'CustomerPaymentController@index')->name('customer.payments.index');
    Route::post('CustomerPayment/GetCustomerPayments', 'CustomerPaymentController@GetCustomerPayments')->name('customer.payments.record.list');
    Route::post('CustomerPayment/ActionCustomerPayments', 'CustomerPaymentController@ActionCustomerPayments')->name('action.customer.payments');
    Route::any('CustomerPayment/delete/{id}', 'CustomerPaymentController@destroy')->name('customer.payments.delete');
    Route::post('CustomerPayment/ChangeStatus/{id}/{status}', 'CustomerPaymentController@ChangePaymentStatus')->name('customer.payments.change.status');
    Route::get('CustomerPayment/edit/{customerpayment}', 'CustomerPaymentController@edit')->name('customer.payments.edit');
    Route::post('CustomerPayment/update_payment', 'CustomerPaymentController@update')->name('customer.payments.update.payment');
    
    //create
    Route::post('CustomerPayments/store', 'CustomerPaymentController@store')->name('customer.payments.store');

});

Route::group(['prefix' => 'trax/', 'namespace' => 'Shipment', 'middleware' => 'auth:user,admin','as'=> 'trax.'],function(){
    
    //view
    Route::post('calculate-charges', 'TraxController@calculate_charges')->name('trax.calculate.charges');
    Route::post('get-fare-list', 'TraxController@get_fare_list')->name('trax.fare_list'); 
    
    //create
    Route::post('create-booking', 'TraxController@CreateBulkBookingStore')->name('create.booking');
    Route::post('bookings', 'TraxController@CreateBulkBookingByOrderIds')->name('create.bulk.booking');
    Route::get('shipment/', function () {return view('client.orders.manual-orders.trax.create-bulk-booking-by-scan');})->name('create.bulk.booking.by.scan');
    Route::get('get-shipment-slip/{tracking_number}', 'TraxController@TraxGetShipmentSlip')->name('get.shipment.slip');
}); 
 
Route::group(['prefix' => 'leopord/', 'namespace' => 'Shipment', 'middleware' => 'auth:user,admin','as'=> 'leopord.'],function()
{
    
    Route::get('get-tariff-details/{weight}/{origion_city}/{destination_city}/{cod}', 'LeopordController@LeopordGetTariffDetails')->name('shipment.get.tariff.details');
    Route::post('calculate-tariff-charges', 'LeopordController@LeopordCalculateDeliveryCharges')->name('shipment.calculate.tariff.charges');
    Route::get('get-shipment-slip/{tracking_number}', 'LeopordController@LeopordGetShipmentSlip')->name('get.shipment.slip');;
    
    
    Route::get('get-cn-details/{cn}', 'LeopordController@GetCnDetails')->name('get.cn.details');
    Route::get('loadsheet', 'LeopordController@Loadsheet')->name('loadsheet');
    Route::get('generate-loadsheet', 'LeopordController@GenerateLoadsheet')->name('generate.loadsheet');
    Route::post('print-loadsheet', 'LeopordController@PrintLoadsheet')->name('print.loadsheet');
    Route::get('generate-cities', 'LeopordController@GenerateLeopordCities')->name('generate.cities');
    
});

Route::group(['prefix' => 'leopord/', 'namespace' => 'Shipment','as'=> 'leopord.'],function()
{
    Route::get('track-boocked-packet/{tracking_number}', 'LeopordController@LeopordTrackBookedPacket')->name('track.boocked.packet');
}); 

Route::group(['prefix' => 'social/', 'namespace' => 'social', 'middleware' => 'auth:user,admin','as'=> 'social.'],function()
{
    
    Route::get('facebook', 'facebookController@index')->name('facebook.index');

    
    
    
    
    
});
//{weight}/{shipment_type}/{origion_city}/{destination_city}/{cod}
//https://manualordersstaging.brandhub.com.pk/get-tariff-details/1000/2/789/76/5000
/*==================================
            Admin Routes
====================================*/
Route::group(['prefix' => 'admin/', 'namespace' => 'Admin', 'middleware' => 'auth:user,admin' ],function(){
    
    //Inventory view
    Route::any('inventory/list/{status?}/{date_from?}/{date_to?}', 'InventoryController@index')->name('inventory.index');
    Route::post('inventory/getproduct', 'InventoryController@getproduct')->name('inventory.get.product');
    Route::any('inventory/pos', 'InventoryController@pos')->name('inventory.pos');
    
    //Inventory create
    Route::post('inventory/store', 'InventoryController@store')->name('inventory.store');
    
    //Inventory edit
    Route::get('inventory/edit/{inventory}', 'InventoryController@edit')->name('inventory.edit');
    Route::post('inventory/update/{inventory}', 'InventoryController@update')->name('inventory.update');
    
    //Inventory delete
    Route::get('inventory/deletcustomerproduct/{inventory_id}', 'InventoryController@deletcustomerproduct')->name('inventory.delete.customer.product');

    //Accounts view
    Route::get('accounts/', 'AccountsController@index')->name('accounts.index'); 
    Route::get('accounts/shipment/status/list/{status}/{date_from}/{date_to}', 'AccountsController@ShipmentStatusList')->name('admin.accounts.shipment.status.list');
    Route::get('accounts/tracking/status/list/{status}/{date_from}/{date_to}', 'AccountsController@TrackingStatusList')->name('admin.accounts.tracking.status.list');
    Route::get('dashboard/', 'DashboardController@index')->name('admin.dashboard');
    Route::post('dashboard/', 'DashboardController@index')->name('admin.dashboard.monthly'); 
    
    //Accounts edit
    Route::any('accounts/update_shipment_payments/{id}/{order_id}', 'AccountsController@UpdateShipmentPaymentStatus')->name('update.shipment.status');
    Route::post('update_bulk_shipment_payments/', 'AccountsController@UpdateBulkShipmentPaymentStatus')->name('update.bulk.shipment.status');
    
    //cron
    Route::get('crone_update_shipment_payments/', 'AccountsController@CroneUpdateShipmentPaymentStatuss')->name('crone.update.shipment.payment.status');
    Route::get('crone_update_shipment_tracking/', 'AccountsController@CroneUpdateShipmentTrackingStatus')->name('crone.update.shipment.tracking.status');
    
    //Product Demand
    Route::get('product/demand/list/', 'ProductDemandController@index')->name('product.demand.list'); 
    Route::post('product/demand/create/', 'ProductDemandController@create_product_demand')->name('create.product.demand');
    Route::post('product/demand/destroy/{id}', 'ProductDemandController@destroy')->name('destroy.product.demand');  
    
    
     
});

Route::group(['prefix' => 'admin','namespace' => 'Auth\Admin','as'=> 'admin.'],function(){
    
    //view
    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('logout', 'LoginController@logout')->name('logout');
    
    //create
    Route::post('login', 'LoginController@login');
    
    Route::post('create', 'LoginController@logout')->name('create');
});

Route::group(['middleware' => ['auth' => 'admin'] , 'prefix' => 'admin/user/','namespace' => 'Auth\Admin','as'=> 'admin.user.'],function(){

    //view
    Route::resource('user', 'UserController');
    Route::get('', 'UserController@index')->name('index');
    
    
    //create
    Route::get('create/', 'UserController@create')->name('create');
    Route::post('create/', 'UserController@store')->name('store');
    
    //edit
    Route::get('{user}/edit/', 'UserController@edit')->name('edit');
    Route::put('{user}', 'UserController@update')->name('update');
    Route::get('permisssions/{user_id}/{page_id}/{permission_type}', 'UserController@update_page_permissions')->name('update.user.permissions');
    
    //delete
    Route::delete('{user}', 'UserController@destroy')->name('destroy'); 
    Route::get('create/', 'UserController@create')->name('show');
    
});
 
//Public routes without guard
Route::group(['prefix' => 'frontend/client/orders/', 'namespace' => 'Frontend\Client\Orders','as'=> 'Frontend.'],function(){ 
    
    Route::resource('ManualOrders', 'ManualOrdersController');
    Route::get('ManualOrders/client/authentication/', 'ManualOrdersController@authentication')->name('ManualOrders.authentication');
    Route::post('ManualOrders/client/authentication/', 'ManualOrdersController@verify_authentication')->name('ManualOrders.create.guest.cookie');
    Route::post('ManualOrders/client/authentication/', 'ManualOrdersController@verify_authentication')->name('Frontend.create.order'); 
});

//Public Route access
Route::get('ManualOrders/create', 'Frontend\Client\Orders\ManualOrdersController@create')->name('publlic.manualorders.create');
Route::get('click-here-to-confirm-your-order/{id}', 'Frontend\Client\Orders\ManualOrdersController@customer_order_confirmation')->name('ManualOrders.confirm.order.by.customer.show');
Route::get('ManualOrders/order-confirmed/{ManualOrder}', 'Frontend\Client\Orders\ManualOrdersController@customer_order_confirmed')->name('ManualOrders.confirm.order.by.customer');
Route::get('trackorder/', 'Frontend\Client\Orders\ManualOrdersController@TrackOrder')->name('ManualOrders.track.order');
Route::get('trackorder/{id}', 'Frontend\Client\Orders\ManualOrdersController@TrackOrderDetails')->name('ManualOrders.track.order.details');
Route::post('trackorder/list', 'Frontend\Client\Orders\ManualOrdersController@GetOrdersByNumber')->name('ManualOrders.get.order.list');
Route::get('trackorder/list', 'Frontend\Client\Orders\ManualOrdersController@TrackOrder')->name('ManualOrders.get.order.list.all');
    
//rider guard
Route::group(['prefix' => 'riders','as'=> 'riders.'],function(){
    Route::get('/login', 'RidersController@showLoginForm')->name('login');
    Route::post('login', 'RidersController@login');
    Route::post('logout', 'RidersController@logout')->name('logout');

});

Route::group(['prefix' => 'riders', 'as'=> 'riders.'],function(){

    //view
    Route::resource('/', 'RidersController')->except('show')->middleware('auth:admin'); 
    Route::get('/dashboard', 'RidersController@dashboard')->name('dashboard')->middleware('auth:rider'); 
    Route::get('/list', 'RidersController@list')->name('list')->middleware('auth:user,admin');

    //create
    Route::post('/generate-loadsheet', 'LoadSheetController@generate_load_sheet')->name('generate.load.sheet'); 
    
}); 


 
URL::forceScheme('https');
