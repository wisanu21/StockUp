@extends('layout.app')
@section('content')
<style>
.container {
  position: relative;
  text-align: center;
  color: white;
}

.centered {
  position: absolute;
  top: 86%;
  left: 50%;
  transform: translate(-50%, -50%);
}
</style>
    <link href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <div class="">
        <div class = "row justify-content-center">
            <div class="col-xl-8 col-lg-7 col-sm-12">
                <div class="card shadow mb-3">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-secondary"> {!! $menu->html_icon !!}  {!! $menu->name !!} </h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center table-responsive">
                            <table class="table w-100" id="table-list-product">
                                <thead>
                                <tr class="d-none">
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody class=" container-fluid row">
                                @foreach($products as $product)
                                    <tr onclick="addProduct({{$product->id}} , '{{$product->name}}' , {{$product->price}} , '{{$product->image_path}}' )" >
                                        <td>
                                            <div class="card-body">
                                                <div class = "container" style="padding-left: 0px; width:200px;" >
                                                    <!-- <img src="{{url('/storage/product/'.$product->image_path)}}" alt="Snow" style="width: auto;height:200px; max-width:200px;display: block;margin-left: auto;margin-right: auto;"> -->
                                                    <div style="
                                                        padding-top: 50%;
                                                        padding-bottom: 50%;
                                                        background: url({{url('/storage/product/'.$product->image_path)}});
                                                        background-repeat: no-repeat;
                                                        background-size: contain;
                                                        background-position:center;
                                                        background-color: #d1d1d1;
                                                        width: inherit;">
                                                    </div>
                                                    <div>
                                                        <div class="centered"style="width: 100%;background: #362510;">
                                                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="opacity: 0.75;">
                                                                {{$product->name}}</div>
                                                            <div class="h6 mb-0 font-weight-bold " style="opacity: 0.75;">{{$product->price}}  บาท</div>
                                                            @if( $product->is_stock != "0" && $product->Stock != null  )
                                                                <div class="h6 mb-0 font-weight-bold " style="opacity: 0.55;">สินค้าในสต๊อก : {{$product->Stock->number}}  ชิ้น</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-sm-12">
                <div class="card shadow mb-12">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-secondary"> <i class="fab fa-shopify"></i> รายการที่เลือก </h6>
                    </div>
                    <div class="card-body" id = "card-list-product">


                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
@section('script')
    <script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
    $(document).ready( function () {
        $('#table-list-product').DataTable();
    } );

    var list_products = [] ;
    var select_st_promotion = null ;
    var promotion_id = null ;
    setListProductsByLocalStorage();
    showListProducts();

    function setLocalStorageByListProducts(){
        localStorage.setItem('list_products', JSON.stringify(list_products));
    }
    
    function setListProductsByLocalStorage(){
        // console.log(JSON.parse(localStorage.getItem('list_products')));
        if( JSON.parse(localStorage.getItem('list_products')) == null ){
            localStorage.setItem('list_products', JSON.stringify(list_products));
        } 
        list_products = JSON.parse(localStorage.getItem('list_products'));
    }

    function addProduct(product_id , product_name , product_price , product_image_path){
        var index_list_products = findProductOnListProducts(product_id) ;
        if(index_list_products == null){
            list_products[ list_products.length ] = {"id":product_id , "number": 1 , "name" : product_name , "price" : product_price , "image_path" : product_image_path } ;
        }else{
            list_products[index_list_products].number = list_products[index_list_products].number + 1 ;
        }

        setLocalStorageByListProducts();
        showListProducts();
        console.log(list_products ,product_image_path);
    }

    function findProductOnListProducts(product_id){
        for (let index = 0; index < list_products.length; index++) {
            if( list_products[index].id == product_id ){
                return index ;
            }
        }
        return null ;
    }

    function showListProducts(){
        var HTML_box_list_product = "";
        for (let index = 0; index < list_products.length; index++) {
            
            HTML_box_list_product = HTML_box_list_product +
            '<nav class="navbar navbar-expand navbar-light bg-light mb-4 box_list_product">'
                                        +'<div>'+list_products[index].name+'</div>'
                                        +'<div class="navbar-nav ml-auto">'
                                            +'<a  onclick="updateProduct('+ list_products[index].id +' ,'+"'+'"+')" class="btn btn-primary btn-primary-blue btn-sm" style="margin-right: 1px;"><i class="fas fa-plus"></i></a>'
                                            +'<input id = "product-id-'+ list_products[index].id +'" value = '+ list_products[index].number +' product-id = "'+ list_products[index].id +'" type="text" name="product-id-'+ list_products[index].id +'" style="width: 50px;" readonly />'
                                            +'<a  onclick="updateProduct('+ list_products[index].id +' ,'+"'-'"+')" class="btn btn-primary btn-primary-blue btn-sm" style="margin-left: 1px;"><i class="fas fa-minus"></i></a>'
                                        +'</div>'
                                    +'</nav> ' ;
            
        }

        if(list_products.length != 0){
            HTML_box_list_product = HTML_box_list_product + '<select onchange="showPromotionOrCode()" id="select_st_promotion" class = "form-control form-control-user box_list_product" style="margin-bottom: 5px;">'
                +'<option value="">ไม่มีโปรโมชั่น</option>'
                +'<option value="is have promotion">มีโปรโมชั่น</option>'
                +'<option value="is have code">มีโค้ดส่วนลด</option>'
            +'</select>'
            +'<div class = "div-show-promotion-or-code box_list_product"> </div>'
            // var sum_price = exampleCalculateSumPrice()
            HTML_box_list_product = HTML_box_list_product + '<a class="btn btn-primary btn-user btn-block box_list_product submit_order_list" onclick="submit_order_list()" >ราคา $ '+exampleCalculateSumPrice()+' บาท </a>' ;
        }
        
        // $(".box_list_product").remove();
        $('#card-list-product').html(HTML_box_list_product);
        $("#select_st_promotion").val(select_st_promotion)
        showPromotionOrCode();
        
    }

    function updateProduct(product_id , op){
        // console.log($("#product-id-"+product_id).val());
        var index_list_products = findProductOnListProducts(product_id) ;
        if(index_list_products != null){
            if(op == "+"){
                list_products[index_list_products].number = list_products[index_list_products].number + 1 ;
            }
            if(op == "-"){
                list_products[index_list_products].number = list_products[index_list_products].number - 1 ;
            }
            
        }

        findProductIsNumberO();
        setLocalStorageByListProducts();
        showListProducts();
    }

    function findProductIsNumberO(){
        let i = 0 ;
        var ans_list_products = [] ; 
        for (let index = 0; index < list_products.length; index++) {
            
            if( list_products[index].number > 0 ){
                ans_list_products[i] = list_products[index] ;
                i++;
            }
        }
        list_products = ans_list_products ;
        console.log(list_products) 
    }

    function exampleCalculateSumPrice(){
        var sum_price = 0 ;
        for (let index = 0; index < list_products.length; index++) {
            sum_price = sum_price + ( list_products[index].price * list_products[index].number );
        }

        return sum_price ;
    }

    function submit_order_list(){
        setLocalStorageByListProducts()
        // localStorage.setItem('promotion', JSON.stringify(list_products));
        window.location.href = "{{url('order/create-order/')}}"+"/"+promotion_id;
    }

    function showPromotionOrCode(){
        $('.div-show-promotion-or-code').html("");
        select_st_promotion = $("#select_st_promotion").val();
        HTML_promotion_or_code = "" ;
        var promotions = JSON.parse('{!! $promotions !!}');
        // console.log(promotions);
        if(select_st_promotion == "is have promotion"){
            promotion_id = promotions[0].id
            var HTML_promotion_or_code = '<select onchange="setPromotionIdBySelect()" id = "select_promotion" class = "form-control form-control-user box_list_product" style="margin-bottom: 5px;">'
                // +'<option value="">-- กรุณาเลือกโปรโมชั่น --</option>'
            for (let index = 0; index < promotions.length; index++) {     
                HTML_promotion_or_code = HTML_promotion_or_code +'<option value="'+promotions[index].id+'">'+promotions[index].name+'</option>'
            }
            HTML_promotion_or_code = HTML_promotion_or_code + '</select>'
        }

        if(select_st_promotion == "is have code"){
            HTML_promotion_or_code = '<a onclick="calCode()" id = "bt-cal-code"class="btn btn-primary btn-primary-blue " style="float: right"><i id = "icon_cal_code" class="fas fa-bolt"></i></a>'+
            '<div style="overflow: hidden; padding-right: .5em;">'+
                '<input type="text" id = "code" class="form-control form-control-user"placeholder="โค้ด..."style="margin-bottom: 5px;" >'+
            '</div>'
        }
        $('.div-show-promotion-or-code').html(HTML_promotion_or_code);
    }

    function calCode(){
        var promotions = JSON.parse('{!! $promotions !!}');
        console.log(promotions,$("#code").val());
        var st = false
        for (let index = 0; index < promotions.length; index++) {
            if(promotions[index].code == $("#code").val() && promotions[index].is_active_code == 1){
                promotion_id = promotions[index].id ;
                st = true ;
            }
        }
        if(st == true){
            
            $("#bt-cal-code").html('{!! showIconStatus_y_or_n("y") !!}')
        }else{
            promotion_id = null;
            $("#bt-cal-code").html('{!! showIconStatus_y_or_n("n") !!}')
        }
        
    }

    function setPromotionIdBySelect(){
        // console.log($("#select_promotion").val());
        promotion_id = $("#select_promotion").val();
    }

    

    </script>
@endsection
