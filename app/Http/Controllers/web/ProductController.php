<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Menu;
use App\Models\Company ;
use Validator;

class ProductController extends Controller
{
    // const $num_menu = 4 ;
    public function list(){
        $products =  Product::all();
        $menu = Menu::where("id",4)->first();
        return view('product.list', compact('products','menu'));
    }

    public function add(){
   
        return view("product.add");
    }

    public function addSave(Request $request){
        dd($request);
        $validator = $this->validatorAddSave($request);
        if ($validator->errors()->any()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $product = new Product();
        $product->name = $request->name ;
        $product->price = $request->price ;
        $product->is_sell = $request->is_sell ;

    }

    public function validatorAddSave($request){
        $validator = Validator::make(
            $request->all(), [
            'name' => 'required',
            'price' => 'required',
            'is_sell' => 'required',
        ], [
            'name.required' => 'กรุณากรอกชื่อสินค้า',
            'price.required' => 'กรุณากรอกราคาสินค้า',
            'is_sell.required' => 'กรุณาเลือกสถานะสินค้า',
        ]);
        return $validator;
    }

    public function edit($id){

    }

    public function detail($id){
        $product = Product::where("id",$id)->first();
        return  view('product.detail', compact('product'));
    }

    public function delect($id){

    }
}
