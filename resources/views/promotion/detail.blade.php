@extends('layout.app')
@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.4/css/fileinput.min.css" rel="stylesheet" type="text/css">
    <div class="container">
        <div class = "row justify-content-center">
            <div class="col-xl-10 col-lg-10 col-sm-12">
                <div class="card shadow mb-3">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-secondary"> <i class="fas fa-search"></i> รายละเอียดโปรโมชั่น</h6>
                    </div>
                    <div class="card-body">
                    {!! Form::open(['url'=>url("/promotion/add-save"), 'method', 'POST', 'class' => 'form', 'enctype' => 'multipart/form-data']) !!}
                        <div class="form-group row">
                            <div class="col-sm-12">
                                {!! Form::text('name',$promotion->name,['class'=> 'form-control form-control-user', 'placeholder'=>'ชื่อโปรโมชั่น']) !!}
                                {!! showError('name',$errors) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-8">
                                {!! Form::text('resource',$promotion->resource,['class'=> 'form-control form-control-user', 'placeholder'=>'จำนวนส่วนลด']) !!}
                                {!! showError('resource',$errors) !!}
                            </div>
                            <div class="col-sm-4">
                                <?php $price_or_percentages = [""=>"-- กรุณาเลือกหน่วยส่วนลด --" , "price"=>"ลดเป็นจำนวนเงิน ( บาท )" , "percentage"=>"ลดเป็นเปอร์เซ็น ( % )"]; ?>
                                {!! Form::select('price_or_percentage', $price_or_percentages, $promotion->price_or_percentage, ['class'=> 'form-control form-control-user']) !!}
                                {!! showError('price_or_percentage',$errors) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <?php $is_active_promotions = [""=>"-- กรุณาเลือกสถานะการใช้งานโปรโมชั่น --" , "1"=>"เปิดใช้งานโปรโมชั่น" , "0"=>"ปิดใช้งานโปรโมชั่น"]; ?>
                                {!! Form::select('is_active_promotion', $is_active_promotions, $promotion->is_active_promotion, ['class'=> 'form-control form-control-user']) !!}
                                {!! showError('is_active_promotion',$errors) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-8">
                                {!! Form::text('code',$promotion->code,['class'=> 'form-control form-control-user', 'placeholder'=>'โค้ดส่วนลด']) !!}
                                {!! showError('code',$errors) !!}
                            </div>
                            <div class="col-sm-4">
                                <?php $is_active_codes = [""=>"-- กรุณาเลือกสถานะการใช้งานโค้ดส่วนลด --" , "1"=>"เปิดใช้งานโค้ดส่วนลด" , "0"=>"ปิดใช้งานโค้ดส่วนลด"]; ?>
                                {!! Form::select('is_active_code', $is_active_codes, $promotion->is_active_code, ['class'=> 'form-control form-control-user']) !!}
                                {!! showError('is_active_code',$errors) !!}
                            </div>
                        </div>

                        {!! showError('field',$errors) !!}
                        <a class="btn btn-primary btn-user btn-block" href="{{url('/promotion')}}" >กลับ</a>
                    {!! Form::close() !!}
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
