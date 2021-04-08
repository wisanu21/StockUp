@extends('layout.app')
@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.4/css/fileinput.min.css" rel="stylesheet" type="text/css">
    <div class="container">
        <div class = "row justify-content-center">
            <div class="col-xl-10 col-lg-10 col-sm-12">
                <div class="card shadow mb-3">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-secondary"> <i class="fas fa-search"></i> รายละเอียด Order</h6>
                    </div>
                    <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        รหัส Order
                                        {!! Form::text('name',$order->name,['class'=> 'form-control form-control-user']) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        ราคารวม (บาท)
                                        {!! Form::text('sum_price',$order->sum_price,['class'=> 'form-control form-control-user' ]) !!}
                                    </div>
                                    <div class="col-sm-3">
                                        หักโปรโมชั่น (บาท)
                                        {!! Form::text('promotion_price','- '.$order->promotion_price,['class'=> 'form-control form-control-user' ]) !!}
                                    </div>
                                    <div class="col-sm-3">
                                        รับเงิน (บาท)
                                        {!! Form::text('get_money',$order->get_money,['class'=> 'form-control form-control-user' ]) !!}
                                    </div>
                                    <div class="col-sm-3">
                                        ทอนเงิน (บาท)
                                        {!! Form::text('change_money',$order->change_money,['class'=> 'form-control form-control-user' ]) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        เงินสุทธิ (บาท)
                                        {!! Form::text('final_price',$order->final_price,['class'=> 'form-control form-control-user' ]) !!}
                                    </div>
                                </div>
                                

                                <div class="accordion" id="accordionExample">
                                    @foreach($order->order_product as $key => $order_product)
                                        <div class="card">
                                            <div class="card-header" id="headingThree">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree{{$order_product->id}}" aria-expanded="false" aria-controls="collapseThree">
                                                        {{$order_product->Product->name}}
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapseThree{{$order_product->id}}" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <!-- And lastly, the placeholder content for the third and final accordion panel. This panel is hidden by default. -->
                                                
                                                    <div class="form-group row">
                                                        <div class="col-sm-9 row">
                                                            <div class="col-sm-4">
                                                                ชื่อสินค้า
                                                                {!! Form::text('name',$order_product->Product->name,['class'=> 'form-control form-control-user']) !!}
                                                            </div>
                                                            <div class="col-sm-4">
                                                                จำนวน
                                                                {!! Form::text('name',$order_product->num_product,['class'=> 'form-control form-control-user']) !!}
                                                            </div>
                                                            <div class="col-sm-4">
                                                                ราคาที่ขายต่อชิ้น (บาท)
                                                                {!! Form::text('name',$order_product->price,['class'=> 'form-control form-control-user']) !!}
                                                            </div>
                                                            <div class="col-sm-4">
                                                                ราคาที่ขายรวม (บาท)
                                                                {!! Form::text('name',$order_product->price * $order_product->num_product,['class'=> 'form-control form-control-user']) !!}
                                                            </div>
                                                        </div>
                                               
                                                        <div class="col-sm-3 row">
                                                            <div class="col-sm-12">
                                                        
                                                                <div style="
                                                                    padding-top: 50%;
                                                                    padding-bottom: 50%;
                                                                    background: url({{url('/storage/product/'.$order_product->Product->image_path)}});
                                                                    background-repeat: no-repeat;
                                                                    background-size: contain;
                                                                    background-position:center;
                                                                    background-color: #d1d1d1;
                                                                    width: inherit;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                                </br>
                                <a class="btn btn-primary btn-user btn-block" href="{{url('/sales-summary')}}" >กลับ</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.4/js/fileinput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.4/themes/fas/theme.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.4/js/locales/th.js"></script>

    <script type="text/javascript">
        $("select [value='']").attr("disabled", "disabled");
        $(".file").fileinput({
            language: "th",
            theme: "fas",
            showUpload: false,
            showRemove: false
        });
    </script>
@endsection
