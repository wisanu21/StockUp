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
        var HTML_show_get_price = '<input type="number" id = "get_money"  class="form-control form-control-user"placeholder="เงินที่รับ..."style="margin-bottom: 15px;" >'
        +'<a id = "bt-submit-2" class="btn btn-primary btn-user btn-block  " onclick="showGetChangeMoney()" > รับเงิน </a>'

        $('#div-show-get-price').html(HTML_show_get_price);
        $("#bt-submit-1").hide();
    }
    
    function showGetChangeMoney(){
        var get_money = $("#get_money").val();
        var change_money = get_money - final_price
        var HTML_show_get_price = '<div class = "form-group row ">'
            +'<div class="col-sm-3"style="align-self: center;">'
                // +'<label class = "text-center">'
                    +'เงินทอน :'
                // +'</label>'
            +'</div>'
            +'<div class="col-sm-9">'
                +'<input type="number" value="'+change_money+'" id = "change_money" class="form-control form-control-user"placeholder="เงินทอน..."style="margin-bottom: 15px;" >'
            +'</div>'
        +'</div>'
        +'<a id = "bt-submit-final" class="btn btn-primary btn-user btn-block  " onclick="submitOrder()" > บันทึก </a>'
        $('#div-show-get-price').html(HTML_show_get_price);
    }

    function submitOrder(){
        var list_products = JSON.parse(localStorage.getItem('list_products'));
        var data_form = { "products" : list_products , "promotion_id" : null , "final_price" :null}
        axios.post('/setting/salary-employee-save', {items: vm.formData})
        .then((response) => {
            Swal.fire({
                type: 'success',
                title: 'บันทึกข้อมูลสำเร็จ',
                showConfirmButton: false,
                timer: 3000
            })
            setTimeout(function(){
                window.location.href = '/setting/salary-employee';
            }, 3000);
        })
        .catch((err) => {
            vm.errors = JSON.parse(JSON.stringify(err.response.data.errors));
            Swal.fire({
                type: 'error',
                title: 'เกิดข้อผิดพลาด',
            })
        })
    }
    </script>
    
@endsection
