@extends('layout.app')
@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.4/css/fileinput.min.css" rel="stylesheet" type="text/css">
    <div class="container">
        <div class = "row justify-content-center">
            <div class="col-xl-10 col-lg-10 col-sm-12">
                <div class="card shadow mb-3">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-secondary"> <i class="fas fa-plus"></i> เพิ่มของในสต๊อก</h6>
                    </div>
                    <div class="card-body">
                    {!! Form::open(['url'=>url("/manage-stock/add-save"), 'method', 'POST', 'class' => 'form', 'enctype' => 'multipart/form-data']) !!}
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        {!! Form::text('name',old('name'),['class'=> 'form-control form-control-user', 'placeholder'=>'ชื่อ']) !!}
                                        {!! showError('name',$errors) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        {!! Form::text('number',old('number'),['class'=> 'form-control form-control-user', 'placeholder'=>'จำนวนในสต๊อก']) !!}
                                        {!! showError('number',$errors) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        
                                        {!! Form::file('image',  ['class'=> 'file' ,"data-browse-on-zone-click"=> 'true']) !!}
                                        {!! showError('image',$errors) !!}
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
            showRemove: false
        });
    </script>
@endsection
