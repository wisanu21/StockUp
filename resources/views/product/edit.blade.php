@extends('layout.app')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.4/css/fileinput.min.css" rel="stylesheet" type="text/css">
    <div class="container">
        <div class = "row justify-content-center">
            <div class="col-xl-10 col-lg-10 col-sm-12">
                <div class="card shadow mb-3">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-secondary"> <i class="fas fa-edit"></i> แก้ไขสินค้า</h6>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['url'=>url("/product/edit-save/"), 'method', 'POST', 'class' => 'form', 'enctype' => 'multipart/form-data']) !!}
                            {!! Form::hidden('id',$product->id)!!}
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    {!! Form::text('name',old("name",$product->name),['class'=> 'form-control form-control-user', 'placeholder'=>'ชื่อสินค้า']) !!}
                                    {!! showError('name',$errors) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    {!! Form::text('price',old("name",$product->price),['class'=> 'form-control form-control-user', 'placeholder'=>'ราคาสินค้า']) !!}
                                    {!! showError('price',$errors) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <?php $is_sell = [""=>"-- กรุณาเลือกสถานะการขาย --" , "0"=>"ระงับการขาย" , "1"=>"ทำการขาย"]; ?>
                                    {!! Form::select('is_sell', $is_sell,old("name",$product->is_sell), ['class'=> 'form-control form-control-user']) !!}
                                    {!! showError('is_sell',$errors) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    {!! Form::file('image',  ['class'=> 'file']) !!}
                                    {!! showError('image',$errors) !!}
                                </div>
                            </div>
                            <hr class="sidebar-divider my-0">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                <?php $is_stocks = [""=>"-- กรุณาเลือกสถานะตัดสต๊อก --" , "1"=>"ทำการตัดสต๊อก" , "0"=>"ไม่ทำการตัดสต๊อก"]; ?>
                                    {!! Form::select('is_stock', $is_stocks, old('is_stock',$product->is_stock), ['class'=> 'form-control form-control-user is_stock']) !!}
                                    {!! showError('is_stock',$errors) !!}
                                </div>
                            </div>
                            <?php 
                            if($product->stock != null){
                                $number = $product->stock->number ; 
                            }else{
                                $number = null ;
                            }
                            ?>
                            <div class="form-group row div_inventory">
                                <div class="col-sm-12">
                                    {!! Form::number('number',old('number',$number),['class'=> 'form-control form-control-user', 'placeholder'=>'จำนวนสินค้า']) !!}
                                    {!! showError('number',$errors) !!}
                                </div>
                            </div>
                            {!! showError('field',$errors) !!}
                            <input type="submit"  class="btn btn-primary btn-user btn-block" value="บันทึก">
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
            showRemove: false,
            showClose: false,
            // showBrowse: false,
            initialPreviewShowDelete: false,
            // browseOnZoneClick: false ,
            initialPreview: '<img src="'+ "{{url('/storage/product/'.$product->image_path)}}" +'" class="kv-preview-data file-preview-image">',
            
        });
        onChange();
        $(".is_stock").change(function() {
            onChange();
        });
        function onChange(){
            if($(".is_stock").val() == "1"){
                $(".div_inventory").show();
            }else{
                $(".div_inventory").hide();
            }
        }
    </script>
@endsection
