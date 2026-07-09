<?php

namespace App\Http\Controllers;

// HELPERS
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Auth;

// MODEL
use App\Model\Inventory;
use App\Model\Eprpo;

// PACKAGE
use DataTables;
use Carbon\Carbon;

class InventoryController extends Controller
{
    //View Inventories
    public function view_inventories(Request $request){
        if($request->ajax()){

        	if(isset($request->part_id)) {
        		$data = Inventory::select('msi_inventory.*', 'parts.description as p_description', 'db_rapidx.users.name as user_name')
        			->join('parts', 'parts.id', '=', 'msi_inventory.part_id')
        			->join('db_rapidx.users', 'db_rapidx.users.id', '=', 'msi_inventory.created_by')
        			->where('msi_inventory.logdel', 0)
        			->where('msi_inventory.status', $request->status)
        			->where('msi_inventory.type', $request->type)
        			->where('msi_inventory.part_id', $request->part_id)
    				->get();
        	}
        	else {
		        $data = Inventory::select('msi_inventory.*', 'parts.description as p_description', 'db_rapidx.users.name as user_name')
        			->join('parts', 'parts.id', '=', 'msi_inventory.part_id')
        			->join('db_rapidx.users', 'db_rapidx.users.id', '=', 'msi_inventory.created_by')
        			->where('msi_inventory.logdel', 0)
        			->where('msi_inventory.status', $request->status)
        			->where('msi_inventory.type', $request->type)
    				->get();
        	}

	        return DataTables::of($data)
	        	// ->addColumn('raw_created_at', function($row){
	            //     return Carbon::parse($row->created_at)->format('Y-m-d H:i:s'); 
	            // })
	            // ->addColumn('raw_input', function($row){
	            //     return '<span class="text-success">+' . $row->input . '</span>';
	            // })
				// ->addColumn('raw_output', function($row){
	            //     return '<span class="text-danger">-' . $row->output . '</span>';
	            // })
	            // ->addColumn('raw_status', function($row){
	            //     $result = "";

	            //     if($row->status == 1){
	            //         $result .= '<span class="badge badge-pill bg-success">Active</span>';
	            //     }
	            //     else if($row->status == 2){
	            //         $result .= '<span class="badge badge-pill bg-danger">Archived</span>';
	            //     }

	            //     return $result;
	            // })
	            // ->addColumn('raw_action', function($row){
	            //     $result = '';
	            //     if($row->status == 1){
	            //         $result .= '<button type="button" class="btn btn-xs btn-primary table-btns btnEditInventory" reference-type-id="' . $row->id . '"><i class="fa fa-edit" title="Edit"></i></button>';

	            //         $result .= ' <button type="button" class="btn btn-xs btn-danger table-btns btnActions" action="1" status="2" reference-type-id="' . $row->id . '" title="Archive"><i class="fa fa-lock"></i></button>';
	            //     }
	            //     else{
	            //         $result .= ' <button type="button" class="btn btn-xs btn-success table-btns btnActions" action="1" status="1" reference-type-id="' . $row->id . '" title="Restore"><i class="fa fa-unlock"></i></button>';
	            //     }

	            //     return $result;
	            // })
	            ->rawColumns(['raw_status', 'raw_action', 'raw_input', 'raw_output'])
	            ->make(true);
        }
    	else{
    		abort(403);
    	}
    }

    // public function save_inventory(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     session_start();

    //     if($request->ajax()){
	//         if(isset($_SESSION["rapidx_user_id"])){
	// 	        // Add Inventory
	// 	        if(!isset($request->inventory_id)){
	// 	            $data = $request->all();

	// 	            $rules = [
	// 					'part_id' => 'required',
	// 					'type' => 'required',
	// 					'boh' => 'required',
	// 					'input' => 'required',
	// 					'output' => 'required',
	// 					'eoh' => 'required',
	// 	            ];

	// 	            $validator = Validator::make($data, $rules);

	// 	            try {
	// 	                if($validator->passes()){
	// 	                    Inventory::insert([
	// 							'part_id' => $request->part_id,
	// 							'type' => $request->type,
	// 							'boh' => $request->boh,
	// 							'input' => $request->input,
	// 							'output' => $request->output,
	// 							'eoh' => $request->eoh,
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

    // public function get_inventory_by_id(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     session_start();
    //     if($request->ajax()){
	//         if(isset($_SESSION["rapidx_user_id"])){
	// 	        $data = [
	// 	            'inventory_id' => $request->inventory_id,
	// 	        ];

	// 	        $rules = [
	// 	            'inventory_id' => 'required',
	// 	        ];

	// 	        $validator = Validator::make($data, $rules);

	// 	        if($validator->passes()){
	// 	            $inventory_info = Inventory::where('id', $request->inventory_id)->where('logdel', 0)->first();

	// 	            return response()->json(['auth' => 1, 'inventory_info' => $inventory_info, 'result' => 1]);
	// 	        }
	// 	        else{
	// 	            return response()->json(['auth' => 1, 'inventory_info' => null, 'result' => 0]);  
	// 	        }
	// 	    }
	// 	    else{
	//         	return response()->json(['auth' => 0, 'result' => 0, 'error' => null]);
	// 	    }
	// 	}
    // 	else{
    // 		abort(403);
    // 	}
    // }

    // public function get_item_by_rec_no(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     session_start();
    //     if(!$request->ajax()){
	//         if(isset($_SESSION["rapidx_user_id"])){
	// 	        $data = [
	// 	            'receiving_number' => $request->receiving_number,
	// 	        ];

	// 	        $rules = [
	// 	            'receiving_number' => 'required',
	// 	        ];

	// 	        $validator = Validator::make($data, $rules);

	// 	        if($validator->passes()){
	// 	            $item = Eprpo::select(
	// 	            	'receiving_header.receiving_number',
	// 	            	'receiving_header.reference_po_number',
	// 	            	'receiving_header.received_date',
	// 	            	'receiving_header.actual_delivery_date',
	// 	            	'receiving_details.item_id',
	// 	            	'receiving_details.item_name',
	// 	            	'receiving_details.long_description',
	// 	            	'receiving_details.quantity_received',
	// 	            	'receiving_details.unit_price',
	// 	            )
	// 	            ->where('receiving_details.receiving_number', $request->receiving_number)
	// 	            ->join('receiving_details', 'receiving_details.receiving_number', '=', 'receiving_header.receiving_number')
	// 	            ->first();
	// 	            // $item = Eprpo::orderBy('received_date', 'desc')->limit(10)->get();

	// 	            return response()->json(['auth' => 1, 'item' => $item, 'result' => 1]);
	// 	        }
	// 	        else{
	// 	            return response()->json(['auth' => 1, 'item' => null, 'result' => 0]);  
	// 	        }
	// 	    }
	// 	    else{
	//         	return response()->json(['auth' => 0, 'result' => 0, 'error' => null]);
	// 	    }
	// 	}
    // 	else{
    // 		abort(403);
    // 	}
    // }

    // public function get_inventory_boh_eoh(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     session_start();
    //     if($request->ajax()){
	//         if(isset($_SESSION["rapidx_user_id"])){
	// 	        $data = $request->all();

	// 	        $rules = [
	// 	            'type' => 'required',
	// 	            'part_id' => 'required',
	// 	        ];

	// 	        $validator = Validator::make($data, $rules);

	// 	        if($validator->passes()){
	// 	            $inventory_info = Inventory::select('boh', 'eoh')
	// 	            	->where('type', $request->type)
	// 	            	->where('part_id', $request->part_id)
	// 	            	->where('status', 1)
	// 		            ->where('logdel', 0)
	// 		            ->orderBy('created_at', 'desc')
	// 		            ->first();

	// 	            return response()->json(['auth' => 1, 'inventory_info' => $inventory_info, 'result' => 1]);
	// 	        }
	// 	        else{
	// 	            return response()->json(['auth' => 1, 'inventory_info' => null, 'result' => 0]);  
	// 	        }
	// 	    }
	// 	    else{
	//         	return response()->json(['auth' => 0, 'result' => 0, 'error' => null]);
	// 	    }
	// 	}
    // 	else{
    // 		abort(403);
    // 	}
    // }

    // public function inventory_action(Request $request){
    //     date_default_timezone_set('Asia/Manila');
    //     session_start();

    //     if($request->ajax()){
	//         // Change Inventory Status
	//         if(isset($_SESSION["rapidx_user_id"])){
	// 	        if($request->action == 1){
	// 	            $data = [
	// 	                'inventory_id' => $request->inventory_id,
	// 	                'status' => $request->status,
	// 	            ];

	// 	            $rules = [
	// 	                'inventory_id' => 'required',
	// 	                'status' => 'required|numeric',
	// 	            ];

	// 	            $validator = Validator::make($data, $rules);

	// 	            if($validator->passes()){
	// 	                try {
	// 	                    Inventory::where('id', $request->inventory_id)
	// 	                    	->where('logdel', 0)
	// 	                        ->update([
	// 	                            'status' => $request->status,
	// 	                            'last_updated_by' => $_SESSION["rapidx_user_id"],
	// 	                            'updated_at' => date('Y-m-d H:i:s'),
	// 	                        ]);

	// 	                    return response()->json(['auth' => 1, 'result' => 1, 'error']);
	// 	                } 
	// 	                catch (Exception $e) {
	// 	                    return response()->json(['auth' => 1, 'inventory_info' => null]); 
	// 	                }
	// 	            }
	// 	            else{
	// 	                return response()->json(['auth' => 1, 'result' => 0, 'error' => $validator->messages()]);    
	// 	            }
	// 	        }
	//         } // Session Expired
	// 	    else{
	//         	return response()->json(['auth' => 0, 'result' => 0, 'error' => null]);
	// 	    }  
	// 	}
    // 	else{
    // 		abort(403);
    // 	}
    // }

    // public function get_cbo_inventory_by_stat(Request $request){
    //     date_default_timezone_set('Asia/Manila');

    //     if($request->ajax()){
    //     	if(isset($_SESSION["rapidx_user_id"])){
	// 	        $search = $request->search;

	// 	        if($search == ''){
	// 	            $inventories = [];
	// 	        }
	// 	        else{
	// 	            $inventories = Inventory::orderby('description','asc')->select('id','description')
	// 	                        ->where('description', 'like', '%' . $search . '%')
	// 	                        ->where('status', 1)
	// 	                        ->where('logdel', 0)
	// 	                        ->get();
	// 	        }

	// 	        $response = array();
	// 	        $response[] = array(
	//                 "id" => '',
	//                 "text" => '',
	//             );

	// 	        foreach($inventories as $inventory){
	// 	            $response[] = array(
	// 	                "id" => $inventory->id,
	// 	                "text" => $inventory->description,
	// 	            );
	// 	        }

	// 	        echo json_encode($response);
	// 	        exit;
    //     	}
    //     	else{
    //     		$response = array();
	// 	            $response[] = array(
	// 	                "id" => '',
	// 	                "text" => 'Please reload again.',
	// 	            );

	// 	        echo json_encode($response);
    //     	}
    //     }
    // 	else{
    // 		abort(403);
    // 	}
    // }
}
