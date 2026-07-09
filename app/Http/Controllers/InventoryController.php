<?php

namespace App\Http\Controllers;

// HELPERS
use App\Exports\ExportItemInventory;
use App\Model\Eprpo;
use App\Model\EprpoPurchaseOrderDetails;
use App\Model\ItemInventory;
use App\Model\ItemTransactionDetails;
use App\RapidxUser;
use Auth;
use Carbon\Carbon;
// use DataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\Mail\WithdrawalNotification;

class InventoryController extends Controller
{

	public function view_item_inventory(Request $request){
		session_start();
        if($request->ajax()){

			$data = ItemInventory::select(
				'item_inventory.*',
				DB::raw("
					MAX(
						CASE
							WHEN msi_inventory.form = 2
							AND msi_inventory.approval_status = 1
							THEN 1
							ELSE 0
						END
					) as has_pending
				")
			)
			->leftJoin('msi_inventory', 'msi_inventory.item_inventory_id', '=', 'item_inventory.id')
			->where('item_inventory.logdel', 0)
			->groupBy('item_inventory.id')
			->orderByDesc('has_pending')
			->get();

	        return DataTables::of($data)
				->addColumn('action', function($row){
					$result = '';
					$result .= '<center>';
	              	$result .= '<button type="button" class="btn btn-xs btn-primary table-btns btnViewItem" inventory-id="' . $row->id . '"><i class="fa fa-eye" title="Approve"></i></button>';
	                $result .= '</center>';
					return $result;
				})
				// ->addColumn('delivery_date', function($row){
				// 	// If item has transactions

				// })
				// ->addColumn('invoice_no', function($row){
				// 	// If item has transactions

				// })

	            ->addColumn('raw_category', function($row){
	                $result = "";

	                if($row->category == 1){
	                    $result = 'ACU';
	                }
	                else if($row->category == 2){
	                    $result = 'ACU - F2';
	                }
	                else if($row->category == 3){
	                    $result = 'Air Compressor';
	                }
	                else if($row->category == 4){
	                    $result = 'Electrical Spare';
	                }
	                else if($row->category == 5){
	                    $result = 'Bldg.Maintenance';
	                }

	                return $result;
	            })

				->addColumn('raw_status', function($row) {
					// Access the collection of details
					$details = $row->item_transaction_details;

					// Check if any withdrawal (form 2) is pending (status 0)
					$hasPending = $details->where('form', 2)->where('approval_status', 1)->first();

					// return $details;
					// $checkBy = $details->check_by;

					if ($hasPending) {
						$checkBy = $hasPending->checked_by;
						$checkByName = RapidxUser::where('id', $checkBy)->first()->name ?? 'Unknown User';
						// return $checkBy;
						return '<span class="badge badge-warning">For Approval -' . '<br/>'. $checkByName . '</span>';
					}

					// Default status if no pending
					return '<span class="badge badge-success">No Pending Approval</span>';
				})

				->addColumn('raw_unit_price', function($row){
					// Use == for comparison!
					if($row->currency == 2){
						return '₱' . number_format($row->unit_price, 2);
					} else {
						return '$' . number_format($row->unit_price, 2);
					}
				})

				->addColumn('dollar_rate', function($row){
					$result = '<center>';

					if($row->currency == 2){
						$result .= $row->dollar_rate ? number_format($row->dollar_rate, 2) : '-';
					} else {
						$result .= '-'; // Use .= to append to the center tag
					}

					$result .= '</center>';

					return $result;
				})
				->addColumn('converted_up', function($row){
					$result = '<center>';

					if($row->currency == 2){
						// $result .= $row->dollar_rate ? '$' . number_format($row->dollar_rate, 2) : '-';
						$result .= $row->converted_unit_price ? '$'. number_format($row->converted_unit_price, 2) : '-';
					} else {
						$result .= '-'; // Use .= to append to the center tag
					}

					$result .= '</center>';

					return $result;
				})
				->addColumn('current_stocks', function ($row) {
                    $details = collect($row->item_transaction_details);

                    $totalInput = $details->sum('input');

                    // Only approved withdrawals
                    $totalOutput = $details
                        ->where('approval_status', 2)
                        ->sum('output');

                    $min_stock = $row->min_stock;
                    $max_stock = $row->max_stock;
                    $currentEoh = $totalInput - $totalOutput;

                    if ($currentEoh <= $min_stock) {
                        return '<span class="text-danger font-weight-bold">' . $currentEoh . '</span>';
                    } elseif ($currentEoh <= $max_stock) {
                        return '<span class="text-warning font-weight-bold">' . $currentEoh . '</span>';
                    } else {
                        return '<span class="text-success font-weight-bold">' . $currentEoh . '</span>';
                    }
                })

	            ->rawColumns(['raw_status','action', 'raw_category', 'raw_unit_price','dollar_rate','converted_up','current_stocks'])
	            ->make(true);
        }
    	else{
    		abort(403);
    	}
	}

	public function get_item_code(Request $request){

		$item = Eprpo::select(
			'item.item_code',
			'unit_of_measure.unit_of_measure_code',
			'receiving_details.item_id',
			'receiving_details.item_name',
			'receiving_details.long_description',
			'receiving_details.unit_price',
			'receiving_header.currency'
		)
		->join('receiving_details', 'receiving_details.receiving_number', '=', 'receiving_header.receiving_number')
		->join('purchase_order_header', 'purchase_order_header.order_number', '=', 'receiving_header.reference_po_number')
		->join('item', 'item.id', '=', 'receiving_details.item_id')
		->join('unit_of_measure', 'unit_of_measure.id', '=', 'item.unit_of_measure_id')
		->where('purchase_order_header.reference_requisition_number', 'like', '%FCLTY-%')
		// We group by the name and description to collapse duplicates
		->groupBy(
			'item.item_code',
			'unit_of_measure.unit_of_measure_code',
			'receiving_details.item_id',
			'receiving_details.item_name',
			'receiving_details.long_description',
			'receiving_details.unit_price',
			'receiving_header.currency'
		)
		// Use MAX() for the date if you want to order by the most recent receipt
		// without including the date in your unique item list
		->orderByRaw('MAX(receiving_header.received_date) DESC')
		->get();

		return response()->json(['auth' => 1, 'item' => $item, 'result' => 1]);
	}

	public function viewTransactionDetails(Request $request) {
		// Remove session_start(); Laravel handles this for you!
		session_start();

		if($request->ajax()){
			$data = ItemTransactionDetails::where('msi_inventory.logdel', 0)
					->where('msi_inventory.item_inventory_id', $request->item_id)
					->join('db_rapidx.users', 'db_rapidx.users.id', '=', 'msi_inventory.created_by')
					->leftJoin('db_rapidx.users as c', 'c.id', '=', 'msi_inventory.checked_by')
					// It is safer to select columns explicitly when using subqueries
					->select(
						'msi_inventory.*',
						'db_rapidx.users.name as user_name',
						'c.name as checked_by_name'
					)
					// Subquery for EOH (Running total including current row)
					->selectSub(function ($query) {
						$query->selectRaw('IFNULL(
							SUM(CASE WHEN form = 1 THEN input ELSE 0 END) -
							SUM(CASE WHEN form = 2 AND approval_status = 2 THEN output ELSE 0 END)
						, 0)')
						->from('msi_inventory as sub')
						->whereColumn('sub.item_inventory_id', 'msi_inventory.item_inventory_id')
						->where('sub.logdel', 0)
						->where('sub.id', '<=', DB::raw('msi_inventory.id'));
					}, 'computed_eoh')

					// Subquery for BOH (Running total before current row)
					->selectSub(function ($query) {
						$query->selectRaw('IFNULL(
							SUM(CASE WHEN form = 1 THEN input ELSE 0 END) -
							SUM(CASE WHEN form = 2 AND approval_status = 2 THEN output ELSE 0 END)
						, 0)')
						->from('msi_inventory as sub')
						->whereColumn('sub.item_inventory_id', 'msi_inventory.item_inventory_id')
						->where('sub.logdel', 0)
						->where('sub.id', '<', DB::raw('msi_inventory.id'));
					}, 'computed_boh')
					// ->orderBy('msi_inventory.id', 'asc');
					->orderBy('msi_inventory.id', 'desc');

			return DataTables::of($data)
				->editColumn('boh', function($row){
					return '<span>' . number_format($row->computed_boh, 2) . '</span>';
				})
				->editColumn('in', function($row){
					return ($row->form == 1) ? '<span class="text-success">+' . number_format($row->input, 2) . '</span>' : '0.00';
				})
				->editColumn('out', function($row){
					if($row->form == 2){
						return '<span class="text-danger">-' . number_format($row->output, 2) . '</span>';
					}
					return '0.00';
				})
				->editColumn('eoh', function($row){
					return '<strong>' . number_format($row->computed_eoh, 2) . '</strong>';
				})
				->editColumn('transaction_date', function($row){
					// return $row;
					if($row->transaction_date != null){
						return \Carbon\Carbon::parse($row->transaction_date)->format('Y-m-d');
					}else{

						return \Carbon\Carbon::parse($row->created_at)->format('Y-m-d');
					}
				})
				->addColumn('del_date', function($row){
					$result = '';
					if($row->delivery_date != null || $row->delivery_date != '') {
						$result = \Carbon\Carbon::parse($row->delivery_date)->format('Y-m-d');
					}else{
						$result = '-';
					}
					return $result;
				})
				->addColumn('supp_name', function($row){
					$result = '';
					if($row->supplier_name != null || $row->supplier_name != '') {
						$result =  $row->supplier_name;
					}else{
						$result = 'N/A';
					}
					return $result;
				})
				->addColumn('inv_no', function($row){
					$result = '';
					if($row->reference_no != null || $row->reference_no != '') {
						$result = $row->reference_no;
					}else{
						$result = 'N/A';
					}
					return $result;
				})
				->addColumn('action', function($row) {
					$result = '<center>';

					if ($row->form == 2) {
						$row->remarks;
						if ($row->approval_status == 1) { // Pending
							if (isset($_SESSION["rapidx_user_id"]) && $_SESSION["rapidx_user_id"] == $row->checked_by) {

								// First Button: Approve
								$result .= '<button type="button" class="btn btn-xs btn-success btnApproveWithdrawal"
												data-id="' . $row->id . '"
												data-qty="' . $row->output . '"
												title="Approve Withdrawal">
												<i class="fa fa-check"></i> Approve
											</button>';

								// The Break Tag to push the next button down
								$result .= '<br>';

								// Second Button: Reject (added mt-1 for a little vertical spacing)
								$result .= '<button type="button" class="btn btn-xs mt-1 btn-danger btnRejectWithdrawal"
												data-id="' . $row->id . '"
												title="Reject Withdrawal">
												<i class="fa fa-times"></i> Reject
											</button>';

							} else {
								$result .= '<span class="badge badge-warning">Waiting for Approval</span>';
							}
						} elseif ($row->approval_status == 2) {
							$result .= '<span class="badge badge-success">Approved</span>';
						} elseif ($row->approval_status == 3) { // Rejected
							$result .= '<span class="badge badge-danger">Rejected</span>';

							if (!empty($row->remarks)) {
								// 1. Split the string by the separator we used in the controller
								$parts = explode(" | REJECTED: ", $row->remarks);

								// 1. If there is a separator, we have an original note AND a reason
								if (isset($parts[1])) {
									// $originalNote = trim($parts[0]);
									$rejectionReason = trim($parts[1]);
									// Only show the Reason if it's not empty
									if ($rejectionReason !== '') {
										$result .= '<br><small><b class="text-danger">Reason:</b> '.'<br>' . $rejectionReason . '</small>';
									}
								}
							}
						}
					}
					else {
						$result .= '<span class="badge badge-info">Received</span>';
					}

					$result .= '</center>';
					return $result;
				})
				->addColumn('raw_remarks', function($row) {
					$result = '';
					if (!empty($row->remarks)) {
						$parts = explode(" | REJECTED: ", $row->remarks);
						if (isset($parts[1])) {
							$originalRemarks = $parts[0];

							$result = $originalRemarks;
						}else{
							$result = $row->remarks;
						}
						return $result;
					}
				})

				->rawColumns(['boh', 'in', 'out', 'eoh', 'transaction_date', 'del_date', 'supp_name', 'inv_no', 'action','raw_remarks'])
				->make(true);
		}
	}

    // public function save_inventory(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     session_start();

	// 	$data = $request->all();

	// 	// return $data;

    //     if($request->ajax()){
	//         if(isset($_SESSION["rapidx_user_id"])){
	// 	        // Add Inventory
	// 	        if(!isset($request->inventory_id)){
	// 	            $data = $request->all();

	// 	            $rules = [
	// 					'receiving_no' => 'required',
	// 					'category' => 'required',
	// 					'po_no' => 'required',
	// 					'item_id' => 'required',
	// 					'item_name' => 'required',
	// 					// 'item_code' => 'required',
	// 					// 'quantity' => 'required',
	// 					'unit_price' => 'required',
	// 					'delivery_date' => 'required',
	// 					// 'type' => 'required',
	// 					// 'boh' => 'required',
	// 					// 'eoh' => 'required',
	// 	            ];

	// 	            $validator = Validator::make($data, $rules);

	// 				$item = $request->item_name;
	// 				$afterSlash = explode('/', $item)[1];
	// 				$onlyName = explode('(', $afterSlash)[0];
	// 				$itemName = trim($onlyName);

	// 				// return $itemName;

	// 	            try {
	// 	                if($validator->passes()){
	// 					Inventory::insert([
	// 							'type' => $request->type,
	// 							'receiving_no' => $request->receiving_no,
	// 							'reference_no' => $request->reference_no,
	// 							'category' => $request->category,
	// 							'po_no' => $request->po_no,
	// 							'item_id' => $request->item_id,
	// 							'item_name' => $itemName,
	// 							'item_code' => $request->item_code,
	// 							'item_description' => $request->item_description,
	// 							// 'quantity' => $request->quantity,
	// 							'unit_price' => $request->unit_price,
	// 							'delivery_date' => $request->delivery_date,
	// 							'supplier_name' => $request->supplier_name,
	// 							// 'input' => $request->quantity,
	// 							// 'output' => null,
	// 							// 'boh' => $request->boh,
	// 							// 'eoh' => $request->eoh,
	// 							'remarks' => $request->remarks,
	// 	                        'status' => 1,
	// 	                        'created_by' => $_SESSION["rapidx_user_id"],
	// 	                        'last_updated_by' => $_SESSION["rapidx_user_id"],
	// 	                        'created_at' => date('Y-m-d H:i:s'),
	// 	                        'updated_at' => date('Y-m-d H:i:s'),
	// 	                    ]);
	// 	                    return response()->json(['auth' => 1, 'result' => 1, 'error' => null]);
	// 	                }
	// 	                else{
	// 	                    return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
	// 	                }
	// 	            }
	// 	            catch(\Exception $e) {
	// 	                return response()->json(['auth' => 1, 'result' => 0, 'error' => $e]);
	// 	            }
	// 	        }
	// 	        // Edit Inventory
	// 	        else{
	// 	            $data = $request->all();

	// 	            $rules = [
	// 	                'inventory_id' => 'required|numeric',
	// 	                'description' => 'required|min:2|unique:inventories,description,' . $request->inventory_id,
	// 	            ];

	// 	            $validator = Validator::make($data, $rules);

	// 	            try {
	// 	                if($validator->passes()){
	// 	                    Inventory::where('id', $request->inventory_id)
	// 	                    	->where('logdel', 0)
	// 	                    	->where('status', 1)
	// 	                        ->update([
	// 	                            'description' => $request->description,
	// 	                            'last_updated_by' => $_SESSION["rapidx_user_id"],
	// 	                            'updated_at' => date('Y-m-d H:i:s'),
	// 	                        ]);
	// 	                    return response()->json(['auth' => 1, 'result' => 1, 'error' => null]);
	// 	                }
	// 	                else{
	// 	                    return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
	// 	                }
	// 	            }
	// 	            catch(\Exception $e) {
	// 	                return response()->json(['auth' => 1, 'result' => 0, 'error' => $e]);
	// 	            }
	// 	        }
	//         }
	//         else{
	//         	return response()->json(['auth' => 0, 'result' => 0, 'error' => null]);
	//         }
	//     }
    // 	else{
    // 		abort(403);
    // 	}
    // }

    public function getItemDetailsByid(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();
        if($request->ajax()){
	        if(isset($_SESSION["rapidx_user_id"])){
		        $data = $request->all();

		        $rules = [
		            'item_id' => 'required',
		        ];

		        $validator = Validator::make($data, $rules);

				if($validator->passes()){
					$itemDetails = ItemInventory::with(['item_transaction_details'])
					->where('id', $request->item_id)
					->where('logdel',0)
					->first();

					// return $itemDetails;
					return response()->json(['auth' => 1, 'item' => $itemDetails, 'result' => 1]);
				}

		    }
		    else{
	        	return response()->json(['auth' => 0, 'result' => 0, 'error' => null]);
		    }
		}
    	else{
    		abort(403);
    	}
    }

	public function saveItemDetails(Request $request){
		date_default_timezone_set('Asia/Manila');
        session_start();


        if($request->ajax()){
			$exists = ItemInventory::where('item_code', $request->add_item_code)
			->where('unit_price', $request->unit_price)
			->where('dollar_rate', $request->dollar_rate)
			->where('logdel', 0)
			->exists();

			if ($exists) {
				return response()->json([
					'auth' => 1,
					'result' => 2,
					'error' => ['duplicate' => ['Item already exists with same code, price, and dollar rate']]
				]);
			}
			// return 'qwe';
			// Add Inventory
			if(!isset($request->item_id)){
				$data = $request->all();
				// return $data;

				$rules = [
					'category' => 'required',
					'add_item_name' => 'required',
					'add_item_code' => 'required',
				];

				$validator = Validator::make($data, $rules);

					if($validator->passes()){
					$data = [
						'category'         => $request->category,
						'item_id'          => $request->selected_item_id,
						'item_code'        => $request->add_item_code,
						'item_name'        => $request->add_item_name,
						'min_stock'        => $request->min_stock,
						'max_stock'        => $request->max_stock,
						'item_description' => $request->add_item_description,
						'unit_price'       => $request->unit_price,
						'currency'         => $request->currency,
						'item_uom'         => $request->add_uom,
						'remarks'          => $request->add_item_remarks,
						'logdel'           => 0,
						'created_by'       => $_SESSION["rapidx_user_id"],
						'created_at'       => now(), // Laravel helper for date('Y-m-d H:i:s')
					];

					// 2. Add extra fields only if currency is PHP (ID: 2)
					if ($request->currency == 2) {
						$data['dollar_rate']          = $request->dollar_rate;
						$data['converted_unit_price'] = $request->converted_rate;
					}
					ItemInventory::insert($data);

						return response()->json(['auth' => 1, 'result' => 1, 'error' => null]);
					}
					else{
						return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
					}
			}
			// Edit Item
			else{

			}

	    }
    	else{
    		abort(403);
    	};
	}

	public function getItemByItemCode(Request $request){
		date_default_timezone_set('Asia/Manila');
		session_start();

		if($request->ajax()){
			if(isset($_SESSION["rapidx_user_id"])){
				$data = $request->all();

				$rules = [
					'item_code' => 'required',
				];

				$validator = Validator::make($data, $rules);

				if($validator->passes()){

					$itemId = ItemInventory::with(['item_transaction_details'])
					->where('item_code', $request->item_code)
					->where('logdel', 0)
					->value('item_id'); //20685



					// $existingReferences = ItemTransactionDetails::pluck('reference_no')->toArray();
			$existingReferences = ItemTransactionDetails::where('form', 1)
					->where('logdel', 0)
					->pluck('reference_no')
					->toArray();


					$item = EprpoPurchaseOrderDetails::select(
						'purchase_order_details.order_number',
						'purchase_order_details.item_id',
						'purchase_order_details.quantity',
						'receiving_header.actual_delivery_date',
						'receiving_header.other_reference as reference_no',
						'receiving_header.receiving_number',
						// 'receiving_details.quantity_received',
						'supplier.supplier_name'
					)

					->join('item', 'item.id', '=', 'purchase_order_details.item_id')
					->join('receiving_header', 'receiving_header.reference_po_number', '=', 'purchase_order_details.order_number')
					->join('receiving_details', 'receiving_details.receiving_number', '=', 'receiving_header.receiving_number')
					->join('supplier', 'supplier.id', '=', 'receiving_header.supplier_id')
					->where('purchase_order_details.item_id', $itemId)
					// ->where('receiving_header.flag', 1 )
					// ->whereNotIn('receiving_header.other_reference', $existingReferences) // Exclude existing nmodify
					->distinct()
					->orderBy('receiving_header.actual_delivery_date', 'asc')
					->get();

					return response()->json(['auth' => 1, 'item' => $item, 'result' => 1]);
				}
				else{
					return response()->json(['auth' => 1, 'item' => null, 'result' => 0]);
				}
			}
			else{
				return response()->json(['auth' => 0, 'result' => 0, 'error' => null]);
			}

		}
	}

	public function saveItemTransactionDetails(Request $request){
		date_default_timezone_set('Asia/Manila');
		session_start();

		// $data = $request->all();
		// return $data;

		if($request->ajax()){
			// return 'qwe';
			// Add Inventory
			if(!isset($request->item_id)){
				$data = $request->all();
				// return $data;
				$qty = $request->transaction_qty;
				$formType = $request->form_type;

				if($formType == 1) {
					$rules = [
					'transaction_invoice_no' => 'required',
					];
				}else{
					$rules = [
						'withdrawal_approver' => 'required',
					];
				}


				$validator = Validator::make($data, $rules);

					if($validator->passes()){
						if($formType == 1) {
							ItemTransactionDetails::insert([
								'item_inventory_id' => $request->inventory_id,
								'form'   => $formType,
								'receiving_no' => $request->transaction_rcv_no,
								'reference_no' => $request->transaction_invoice_no,
								'po_no' 		=> $request->transaction_po_no,
								'supplier_name' => $request->transaction_supplier_name,
								'delivery_date' => $request->transaction_delivery_date,
								'input'  => ($formType == 1) ? $qty : 0,
								// 'output' => ($formType == 2) ? $qty : 0
								// 'checked_by' => $request->withdrawal_approver, // From the new field
								'remarks' => $request->transaction_remarks,
								'status' => 0,
								'logdel' => 0,
								'created_by' => $_SESSION["rapidx_user_id"],
								'created_at' => date('Y-m-d H:i:s'),
							]);
						}else{

								// Calculate EOH manually right here
								$currentEoh = ItemTransactionDetails::where('item_inventory_id', $request->inventory_id)
									->where('logdel', 0)
									->selectRaw("
										SUM(CASE WHEN form = 1 THEN input ELSE 0 END) -
										SUM(CASE WHEN form = 2 AND approval_status != 3 THEN output ELSE 0 END)
										as total
									")
									->value('total') ?? 0;

								// 2. Check for existing pending withdrawals
								$hasPending = ItemTransactionDetails::where('item_inventory_id', $request->inventory_id)
									->where('form', 2)
									->where('approval_status', 1) // Still Pending
									->where('logdel', 0)
									->exists();

								if ($hasPending) {
									return response()->json([
										'auth' => 1,
										'result' => 2,
										'msg' => "Cannot process withdrawal. There is still a pending withdrawal awaiting approval for this item."
									]);
								}

								// 3. Validation: Insufficient Stock
								if ($qty > $currentEoh) {
									return response()->json([
										'auth' => 1,
										'result' => 2,
										'msg' => "Insufficient stock! Current balance is only " . number_format($currentEoh, 2)
									]);
								}else{

									ItemTransactionDetails::insert([
										'item_inventory_id' => $request->inventory_id,
										'form'   => $formType,
										'receiving_no' => $request->transaction_rcv_no,
										'reference_no' => $request->transaction_invoice_no,
										'po_no' 		=> $request->transaction_po_no,
										'transaction_date' 		=> $request->transaction_date,
										'approval_status' => 1, // Set to Pending Approval
										'output' => ($formType == 2) ? $qty : 0,
										'checked_by' => $request->withdrawal_approver, // From the new field
										'remarks' => $request->transaction_remarks,
										'status' => 0,
										'logdel' => 0,
										'created_by' => $_SESSION["rapidx_user_id"],
										'created_at' => date('Y-m-d H:i:s'),
									]);

									$itemDetailsForEmail = ItemInventory::where('id', $request->inventory_id)->first();


									$emailData = [
										'qty' => $qty,
										'transaction_date' => $request->transaction_date,
										'item_name' => $itemDetailsForEmail->item_name,
										'item_description' => $itemDetailsForEmail->item_description,
										'current_eoh' => $currentEoh,
										'remarks' => $request->transaction_remarks,
									];

									$emailApprover = RapidxUser::where('id', $request->withdrawal_approver)->first();
									$approverEmail = $emailApprover ? $emailApprover->email : 'Unknown User';

									$bcc = ['mrronquez@pricon.ph', 'mlmagdasoc@pricon.ph'];
									// Remove duplicates just in case
									$bcc = array_diff($bcc, [$approverEmail]);

									$mail = Mail::to($approverEmail)
												->bcc($bcc);

									// Only add CC if approver is NOT jdfarcon
									if ($approverEmail !== 'jdfarcon@pricon.ph') {
										$mail->cc('jdfarcon@pricon.ph');
									}

									$mail->send(new WithdrawalNotification($emailData));

								}



						}

						return response()->json(['auth' => 1, 'result' => 1, 'error' => null]);
					}
					else{
						return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);
					}
			}

	    }
		else{
			abort(403);
		}
	}

	public function approveWithdrawal(Request $request)
	{
		// Find the record by ID
		$transaction = ItemTransactionDetails::find($request->id);


		if($transaction) {
			// 1. Mark as Approved (2)
			$transaction->approval_status = 2;

			// 2. Set the approval timestamp using Carbon or PHP date
			// Make sure 'approved_at' exists in your database table
			$transaction->approved_at = now(); // or date('Y-m-d H:i:s')

			// 3. Save the changes
			$transaction->save();

			return response()->json([
				'status' => 0,
				'msg' => 'Withdrawal Approved Successfully!'
			]);
		}

		// If ID is not found
		return response()->json([
			'status' => 1,
			'msg' => 'Transaction not found. ID: ' . $request->id
		]);
	}

	public function rejectWithdrawal(Request $request)
	{
		// Find the record by ID
		$transaction = ItemTransactionDetails::find($request->id);

		// 1. Validate that the reason is provided
			if (empty(trim($request->rejection_remarks))) {
				return response()->json([
					'status' => 2,
					'msg' => 'A rejection reason is required.'
				]);
			}

		if ($transaction) {
			$transaction->approval_status = 3; // Rejected

			$reason = trim($request->rejection_remarks);
			$oldRemarks = trim($transaction->remarks);

			// Build the string cleanly: "Original Remark | REJECTED: Reason"
			if (!empty($oldRemarks)) {
				$transaction->remarks = $oldRemarks . " | REJECTED: " . $reason;
			} else {
				$transaction->remarks = "REJECTED: " . $reason;
			}

			$transaction->save();
			return response()->json(['status' => 0, 'msg' => 'Withdrawal rejected.']);
		}

		// If ID is not found
		return response()->json([
			'status' => 1,
			'msg' => 'Transaction not found. ID: ' . $request->id
		]);
	}

	public function getApproverList(Request $request) {
		date_default_timezone_set('Asia/Manila');
		session_start();

		if($request->ajax()){
			if(isset($_SESSION["rapidx_user_id"])){
				if($_SESSION["rapidx_user_id"] == 141) {
					$approvers = RapidxUser::select('id', 'name')
						->where('user_stat', 1)
						->whereIn('id', [141, 704, 163])
						->where('department_id', 30)
						->get();
				}
				else {
					// For regular users, only show the head of the department as approver
				$approvers = RapidxUser::select('id', 'name')
					->where('user_stat', 1)
					->where('id', '!=', $_SESSION["rapidx_user_id"])
					->whereIn('id', [141, 704, 163])
					->where('department_id', 30)
					->get();
				}

				return response()->json(['auth' => 1, 'approvers' => $approvers, 'result' => 1]);
			}
			else{
				return response()->json(['auth' => 0, 'result' => 0, 'error' => null]);
			}

		}

	}

	// public function exportInventory(Request $request){
	// 	$from = $request->from;
	// 	$to = $request->to;

	// 	$itemDetails = ItemInventory::with(['item_transaction_details' => function($q) use ($from, $to) {
	// 		$q->whereBetween('created_at', [$from, $to]);
	// 	}])
	// 	->where('logdel', 0)
	// 	->get();

	// 	// return $itemDetails;

	// 	return Excel::download(new ExportItemInventory($itemDetails), 'MSI Inventory ' . $from .' - '. $to . '.xlsx');


	// }

    public function exportInventory(Request $request)
    {
        $from = $request->from . ' 00:00:00';
        $to   = $request->to . ' 23:59:59';

        $itemDetails = ItemInventory::with([
            'item_transaction_details' => function ($q) use ($from, $to) {

                // Load ALL transactions up to the selected end date
                $q->where('created_at', '<=', $to)
                ->orderBy('created_at', 'asc');

            }
        ])
        ->where('logdel', 0)
        ->get();

        return Excel::download(
            new ExportItemInventory($itemDetails, $from, $to),
            'MSI Inventory ' . date('Y-m-d', strtotime($from)) . ' - ' . date('Y-m-d', strtotime($to)) . '.xlsx'
        );
    }

	public function check_item_if_exists(Request $request){
		$itemCode = $request->item_code;
		$unitPrice = $request->unit_price;
		$dollarRate = $request->dollar_rate;

		$exists = ItemInventory::where('item_code', $itemCode)
			// ->where('unit_price', $unitPrice)
			// ->where('dollar_rate', $dollarRate)
			->where('logdel', 0)
			->exists();

			// return $exists;
		if($exists) {
			$itemDetails = ItemInventory::where('item_code', $itemCode)
				->where('logdel', 0)
				->first();
				return response()->json([
					'exists' => true,
					'item_id' => $itemDetails->id,
					'min_stock' => $itemDetails->min_stock,
					'max_stock' => $itemDetails->max_stock,

				]);
		}
	}

}
