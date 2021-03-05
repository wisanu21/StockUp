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
    <link href="{{asset('css/print-slip.css')}}" rel="stylesheet" type="text/css">
    <div class="">
        <div class = "row justify-content-center test">
            <div class="col-xl-8 col-lg-7 col-sm-12">
                <div class="card shadow mb-3">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-secondary"> รายละเอียด Order </h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center table-responsive">
                            <table class="table table-bordered" id="table-list-product">
                                <thead class="thead-light">
                                <tr>
                                    <th width="20%"></th>
                                    <th width="20%">ชื่อ</th>
                                    <th width="20%">ราคา (บาท)</th>
                                    <th width="20%">จำนวน (ชิ้น)</th>
                                    <th width="20%">ราคา (บาท)</th>
                                </tr>
                                </thead>
                                <tbody >

                                </tbody>
                            </table>
                            <div id = "div-show-get-price" ></div>
                            <a id = "bt-submit-1" class="btn btn-primary btn-user btn-block  " onclick="showGetPrice()" >ยืนยันการทำ Order </a>
                            <a class="btn btn-warning btn-user btn-block  " onclick="backPage()" >ย้อนกลับ </a>
                        </div>
                        
                    </div>
                </div>
            </div>

        </div>
    </div>
    
@endsection
@section('script')
    <script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <script type="text/javascript">
    $(document).ready( function () {
        // $('#table-list-product').DataTable();
    } );
    var sum_price = 0 ;
    var final_price = 0 ;
    var get_money = 0 ;
    var change_money = 0 ;
    var slip_HTML = '' ;
    var data_detail_order = [] ;
    setTableListDetailProductsByLocalStorage();
    function setTableListDetailProductsByLocalStorage(){
        var list_products = JSON.parse(localStorage.getItem('list_products'));
        console.log(list_products);
        var HTML_in_tbody = "" ;
        for (let index = 0; index < list_products.length; index++) {
            sum_price = sum_price + list_products[index].price*list_products[index].number ;
            HTML_in_tbody = HTML_in_tbody + '<tr>' 
                +'<td >'
                    +'<div style="padding-top: 50%;'
                        +'padding-bottom: 50%;'
                        +'background: url('+'{{url("/storage/product/")}}'+'/'+list_products[index].image_path+');'
                        +'background-repeat: no-repeat;'
                        +'background-size: contain;'
                        +'background-position:center;'
                        +'background-color: #d1d1d1;'
                        +'width: inherit;">'
                    +'</div>'
                +'</td>'
                +'<td>'+list_products[index].name+'</td>'
                +'<td>'+list_products[index].price+'</td>'
                +'<td>'+list_products[index].number+'</td>'
                +'<td>'+ list_products[index].price*list_products[index].number +'</td>'

            +'</tr>'
            // $(".box_list_product").remove();
            
        }

        var num_promotion = 0 ;
        var str_resource = "" ;

        @if($promotion != null)
            HTML_in_tbody = HTML_in_tbody + '<tr>' 
                +'<td colspan="3"></td>'
                +'<td><strong>รวม</strong></td>'
                +'<td>'+sum_price+'</td>'
            +'</tr>'
            if("{{$promotion->price_or_percentage}}" ==  "price"){
                num_promotion = parseFloat("{{$promotion->resource}}");
                str_resource = "{{$promotion->resource}} บาท" ;
            }else{
                num_promotion =  ( sum_price / 100 ) * parseFloat("{{$promotion->resource}}") ;
                str_resource = "{{$promotion->resource}} %" ;
            }
            HTML_in_tbody = HTML_in_tbody + '<tr>' 
                +'<td colspan="2"></td>'
                +'<td colspan="2"><strong>ส่วนลด ( '+"{{$promotion->name}}"+' ลดทันที '+str_resource+' )</strong></td>'
                +'<td> - '+num_promotion+'</td>'
            +'</tr>'
        @endif
        final_price = (sum_price - num_promotion).toFixed(2) ;
        HTML_in_tbody = HTML_in_tbody + '<tr>' 
            +'<td colspan="3"></td>'
            +'<td><strong>ราคาที่ต้องจ่าย</strong></td>'
            +'<td>'+ (sum_price - num_promotion).toFixed(2) +'</td>'
        +'</tr>'

        $('tbody').html(HTML_in_tbody);
    }
    
    function backPage(){
        window.location.href = "{{url('order')}}";
    }

    function showGetPrice(){
        var HTML_show_get_price = '<input type="number" id = "get_money"  class="form-control form-control-user"placeholder="เงินที่รับ... (บาท)"style="margin-bottom: 15px;" >'
        +'<a id = "bt-submit-2" class="btn btn-primary btn-user btn-block  " onclick="showGetChangeMoney()" > รับเงิน </a>'

        $('#div-show-get-price').html(HTML_show_get_price);
        $("#bt-submit-1").hide();
    }
    
    function showGetChangeMoney(){
        get_money = $("#get_money").val();
        change_money = get_money - final_price
        change_money = change_money
        var HTML_show_get_price = '<div class = "form-group row ">'
            +'<div class="col-sm-3"style="align-self: center;">'
                    +'เงินทอน :'
            +'</div>'
            +'<div class="col-sm-7">'
                +'<input type="number" value="'+change_money.toFixed(2)+'" id = "change_money" class="form-control form-control-user"placeholder="เงินทอน..."style="margin-bottom: 15px;" >'
            +'</div>'
            +'<div class="col-sm-2"style="align-self: center;">'
                    +'บาท'
            +'</div>'
        +'</div>'
        +'<a id = "bt-submit-final" class="btn btn-primary btn-user btn-block  " onclick="submitOrder()" > บันทึก </a>'
        $('#div-show-get-price').html(HTML_show_get_price);
    }

    function submitOrder(){
        var promotion_id = null ;
        @if($promotion != null)
            promotion_id = parseInt("{{$promotion->id}}");
        @endif
        var list_products = JSON.parse(localStorage.getItem('list_products'));
        var data_form = { "products" : list_products , "promotion_id" : promotion_id , "final_price" :parseFloat(final_price).toFixed(2) , "get_money" : parseFloat(get_money).toFixed(2) , "change_money" : change_money.toFixed(2)}
        axios.post('/order/submit-order', {items: data_form})
        .then((response) => {
            Swal.fire(
                response.data.title,
                response.data.detail,
                response.data.status
            )
            if(response.data.status == "success"){
                data_detail_order  = response.data.data_detail ;
                showSlip(data_form)
            }
        })
    }

    function showSlip(data_form){
        
        slip_HTML = "<div class = 'row justify-content-center'>"+
            "<div id = 'print-slip-JS' class ='slip-ticket' >"+
                "<style>"+
                    ".slip-ticket { font-size: 10px; font-family: 'Times New Roman'; } td.slip-td, th.slip-th, tr.slip-tr, table.slip-table { border-top: 1px solid black; border-collapse: collapse; } td.slip-description, th.slip-description { width: 75px; max-width: 75px; } td.slip-quantity, th.slip-quantity { width: 40px; max-width: 40px; word-break: break-all; } td.slip-price, th.slip-price { width: 40px; max-width: 40px; word-break: break-all; } .slip-centered { text-align: center; align-content: center; } .slip-ticket { width: 155px; max-width: 155px; } .slip-img { max-width: inherit; width: inherit; } @media print { .hidden-print, .hidden-print * { display: none !important; } }"+
                "</style>"+
                // '<link href="'+'{{asset("css/print-slip.css")}}'+'" rel="stylesheet" type="text/css">'+
                '<img src="'+"{{url('/storage/company/'.\Auth::user()->Company->path_image)}}"+'" alt="Logo" class = "slip-img">'+
                '<p class="slip-centered" >{{\Auth::user()->Company->name}}'+
                    '<br>รหัส Order<br> '+ (data_detail_order.id).toString().padStart(5, '0') +
                    '<br>ชื่อ Order<br> '+ data_detail_order.name +
                '</p>'+
                '<table class = "slip-table">'+
                    '<thead>'+
                        '<tr class="slip-tr" >'+
                            '<th class="slip-th slip-quantity">ลำดับ</th>'+
                            '<th class="slip-th slip-description">สินค้า</th>'+
                            '<th class="slip-th slip-price">ราคา</th>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody>' ;
                    for (let index = 0; index < data_detail_order.products.length; index++) {
                        slip_HTML = slip_HTML + '<tr class="slip-tr">'+
                            '<td class="slip-td slip-quantity">'+( index + 1 ).toString()+'</td>'+
                            '<td class="slip-td slip-description">'+data_detail_order.products[index].name +'</td>'+
                            '<td class="slip-td slip-price">'+data_detail_order.products[index].price +' * '+ data_detail_order.products[index].number +'</td>'+
                        '</tr>'
                    }
                    slip_HTML = slip_HTML + '</tbody>'+
                '</table>'+
                '<p class="slip-centered">ราคารวม : ' + data_detail_order.sum_price + ' บาท' +
                '<p class="slip-centered">ส่วนลด : ' + data_detail_order.promotion_price + ' บาท' +
                '<p class="slip-centered">ราคาที่จ่าย : ' + data_detail_order.final_price + ' บาท' +
                '<p class="slip-centered">เงินที่รับมา : ' + data_detail_order.get_money + ' บาท' +
                '<p class="slip-centered">เงินทอน : ' + data_detail_order.change_money + ' บาท' +
                '<p class="slip-centered">ขอบคุณที่ใช้บริการ'+
                '<br></p>'+
                '<a id = "bt-submit-final" class="btn btn-primary btn-user btn-block  hidden-print" onclick="printSlip()" ><i class="fas fa-print"></i> พิมพ์ใบเสร็จรับเงิน </a>'+
            '</div>'+
        '</div>'
        $('#div-show-get-price').html(slip_HTML);

    }

    function printSlip(){
        w=window.open();
        w.document.write(slip_HTML);
        w.print();
        w.close();
    }
    </script>
    
@endsection
