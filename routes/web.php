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

Route::get('/link', function () {
    return 'link';
})->name('link');


Route::get('/', function () {
    return view('index');
})->name('login');

// Route::get('/dashboard', function () {
//     return view('admin_dashboard');
// })->name('dashboard');

Route::get('/session_expired', function () {
    return view('session_expired');
})->name('session_expired');

// ROUTE CONTROLLER
Route::get('/dashboard', 'RouteController@dashboard')->name('dashboard');
Route::get('/parts', 'RouteController@parts')->name('parts');
Route::get('/inventories', 'RouteController@inventories')->name('inventories');
Route::get('/inventoriesv2', 'RouteController@inventoriesv2')->name('inventoriesv2');
// Route::get('/view_questions/{id}', function ($id) {
// 	session_start();
// 	if(isset($_SESSION["rapidx_user_id"])){
// 		$questionnaire_info = Questionnaire::select('id', 'description')
// 		            ->where('id', $id)
// 		            ->where('status', 1)
// 		            ->where('logdel', 0)
// 		            ->first();

// 		if($questionnaire_info != null){
//     		return view('questions')->with(['id' => $id, 'questionnaire_info' => $questionnaire_info]);
// 		}
// 		else{
// 			return "Questionnaire is not available.";
// 		}
//     }
//     else{
//         return redirect()->route('session_expired');
//     }
// });

// REFERENCE TYPE CONTROLLER
Route::get('/view_reference_types', 'ReferenceTypeController@view_reference_types')->name('view_reference_types');
Route::post('/save_reference_type', 'ReferenceTypeController@save_reference_type')->name('save_reference_type');
Route::post('/reference_type_action', 'ReferenceTypeController@reference_type_action')->name('reference_type_action');
Route::get('/get_reference_type_by_id', 'ReferenceTypeController@get_reference_type_by_id')->name('get_reference_type_by_id');
Route::get('/get_reference_type_by_stat', 'ReferenceTypeController@get_reference_type_by_stat')->name('get_reference_type_by_stat');
Route::get('/get_cbo_reference_type_by_stat', 'ReferenceTypeController@get_cbo_reference_type_by_stat')->name('get_cbo_reference_type_by_stat');

// PART CONTROLLER
Route::get('/view_parts', 'PartController@view_parts')->name('view_parts');
Route::post('/save_part', 'PartController@save_part')->name('save_part');
Route::post('/part_action', 'PartController@part_action')->name('part_action');
Route::get('/get_part_by_id', 'PartController@get_part_by_id')->name('get_part_by_id');
Route::get('/get_part_by_stat', 'PartController@get_part_by_stat')->name('get_part_by_stat');
Route::get('/get_cbo_part_by_stat', 'PartController@get_cbo_part_by_stat')->name('get_cbo_part_by_stat');

// INVENTORY CONTROLLER
// Route::get('/view_inventories', 'InventoryController@view_inventories')->name('view_inventories');
Route::post('/save_inventory', 'InventoryController@save_inventory')->name('save_inventory');
// Route::post('/inventory_action', 'InventoryController@inventory_action')->name('inventory_action');
// Route::get('/get_inventory_by_id', 'InventoryController@get_inventory_by_id')->name('get_inventory_by_id');
// Route::get('/get_inventory_by_stat', 'InventoryController@get_inventory_by_stat')->name('get_inventory_by_stat');
// Route::get('/get_cbo_inventory_by_stat', 'InventoryController@get_cbo_inventory_by_stat')->name('get_cbo_inventory_by_stat');
// Route::get('/get_inventory_boh_eoh', 'InventoryController@get_inventory_boh_eoh')->name('get_inventory_boh_eoh');
// Route::get('/get_item_by_rec_no', 'InventoryController@get_item_by_rec_no')->name('get_item_by_rec_no');
// Route::get('/get_item_by_rec_no_wd', 'InventoryController@get_item_by_rec_no_wd')->name('get_item_by_rec_no_wd');
// Route::get('/get_items_by_rec_no', 'InventoryController@get_items_by_rec_no')->name('get_items_by_rec_no');
// Route::get('/get_items_by_rec_no_wd', 'InventoryController@get_items_by_rec_no_wd')->name('get_items_by_rec_no_wd');
// Route::get('/get_available_parts', 'InventoryController@get_available_parts')->name('get_available_parts');
// Route::get('/get_checked_by', 'InventoryController@get_checked_by')->name('get_checked_by');
// Route::post('/save_withdrawal', 'InventoryController@save_withdrawal')->name('save_withdrawal');
// Route::get('/get_cbo_receiving_no', 'InventoryController@get_cbo_receiving_no')->name('get_cbo_receiving_no');
// Route::get('/get_cbo_items', 'InventoryController@get_cbo_items')->name('get_cbo_items');
// Route::get('/get_wd_items', 'InventoryController@get_wd_items')->name('get_wd_items');


Route::get('/get_item_details_by_id', 'InventoryController@getItemDetailsByid')->name('get_item_details_by_id');
Route::get('/view_item_inventory', 'InventoryController@view_item_inventory')->name('view_item_inventory');
Route::get('/get_item_code', 'InventoryController@get_item_code')->name('get_item_code');
Route::post('/save_item_details', 'InventoryController@saveItemDetails')->name('save_item_details');
Route::get('/check_item_if_exists', 'InventoryController@check_item_if_exists')->name('check_item_if_exists');

Route::get('/get_item_by_item_code', 'InventoryController@getItemByItemCode')->name('get_item_by_item_code');
Route::post('/save_item_transaction_details', 'InventoryController@saveItemTransactionDetails')->name('save_item_transaction_details');

Route::get('/view_transaction_details', 'InventoryController@viewTransactionDetails')->name('view_transaction_details');

Route::get('/get_approver_list', 'InventoryController@getApproverList')->name('get_approver_list');
Route::post('/approve_withdrawal', 'InventoryController@approveWithdrawal')->name('approve_withdrawal');
Route::post('/reject_withdrawal', 'InventoryController@rejectWithdrawal')->name('reject_withdrawal');

// Route::get('/export-inventory', [YourController::class, 'exportInventory']);
// Route::post('/export-inventory', 'InventoryController@exportInventory')->name('export-inventory');
Route::get('/export-inventory', 'InventoryController@exportInventory')->name('export-inventory');