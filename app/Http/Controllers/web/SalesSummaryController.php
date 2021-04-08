<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order ;
use App\Models\Menu;
use Auth ;

class SalesSummaryController extends Controller
{
    public function index(Request $request){
        // dd($request->all());
        $orders = Order::where("company_id", Auth::user()->company_id )->orderBy('id', 'ASC')->get();
        $arr_days = [] ;
        if(count($orders) > 0){
            if($request->all() == [] ){
                $start_date = $orders[0]->created_at->format('Y-m-d');
                $end_date = $orders[ count($orders) - 1 ]->created_at->format('Y-m-d');
            }else{
                $start_date = $request->start_date ;
                $end_date = $request->end_date ;
            }

            $add_day = 0 ;
            // dd($start_date , $end_date , date("Y-m-d",strtotime($start_date."+0 days")) , date("Y-m-d",strtotime($start_date."+".$add_day." days")) <= $end_data);
            while( date("Y-m-d",strtotime($start_date."+".$add_day." days")) <= $end_date ){
                $this_day = date("Y-m-d",strtotime($start_date."+".$add_day." days")) ;
                $arr_days[ $this_day ] = Order::where("company_id", Auth::user()->company_id )->whereDate('created_at', '=', $this_day)->orderBy('id', 'ASC')->get();
                $add_day++;
            }
        }

        $menu = Menu::where("id",10)->first();
        return  view('sales-summary.index', compact('arr_days','menu' , 'start_date' , 'end_date'));

    }

    public function detail($id){
        $order = Order::where("company_id", Auth::user()->company_id )->where("id",$id)->first();
        return  view('sales-summary.detail', compact('order'));
    }
}
