@extends('layout.app')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.4/css/fileinput.min.css" rel="stylesheet" type="text/css">
    <div class="container">
        <div class = "row justify-content-center">
            <div class="col-xl-10 col-lg-10 col-sm-12">
                <div class="card shadow mb-3">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-secondary"> <i class="fas fa-search"></i> รายละเอียด</h6>
                    </div>
                    <div class="card-body">
                    {!! Form::open(['url'=>url("/manage-stock/add-save"), 'method', 'POST', 'class' => 'form', 'enctype' => 'multipart/form-data']) !!}
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        {!! Form::text('name',$stock->name,['class'=> 'form-control form-control-user', 'placeholder'=>'ชื่อ']) !!}
                                        {!! showError('name',$errors) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        {!! Form::text('stock',$stock->number,['class'=> 'form-control form-control-user', 'placeholder'=>'จำนวนในสต๊อก']) !!}
                                        {!! showError('stock',$errors) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        
                                        {!! Form::file('image',  ['class'=> 'file']) !!}
                                        {!! showError('image',$errors) !!}
                                    </div>
                                </div>

                                {!! showError('field',$errors) !!}
                                <a class="btn btn-primary btn-user btn-block" href="{{url('/manage-stock')}}" >กลับ</a>
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
            showBrowse: false,
            initialPreviewShowDelete: false,
            browseOnZoneClick: false ,
            initialPreview: '<img src="'+ "{{url('/storage/stock/'.$stock->image_path)}}" +'" class="kv-preview-data file-preview-image">',
            
        });
    </script>
@endsection
