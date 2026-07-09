<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class RouteController extends Controller
{
    public function dashboard(){
    	session_start();
        if(isset($_SESSION["rapidx_user_id"])){
			return view('admin_dashboard');
    	}
    	else{
    		return redirect()->route('session_expired');
    	}
    }

    // public function parts(){
    //     session_start();
    //     if(isset($_SESSION["rapidx_user_id"])){
    //         return view('parts');
    //     }
    //     else{
    //         return redirect()->route('session_expired');
    //     }
    // }

    public function inventories(){
        session_start();
        if(isset($_SESSION["rapidx_user_id"])){
            return view('inventories');
        }
        else{
            return redirect()->route('session_expired');
        }
    }

    public function inventoriesv2(){
        session_start();
        if(isset($_SESSION["rapidx_user_id"])){
            return view('inventoriesv2');
        }
        else{
            return redirect()->route('session_expired');
        }
    }
}
