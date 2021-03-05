@extends('layout.app')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.4/css/fileinput.min.css" rel="stylesheet" type="text/css">
    <div class="container">
        <div class = "row justify-content-center">
            <div class="col-xl-10 col-lg-10 col-sm-12">
                <div class="card shadow mb-3">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-secondary"> <i class="fas fa-edit"></i> แก้ไขผู้ใช้งานระบบ</h6>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['url'=>url("/manage-users/edit-save/"), 'method', 'POST', 'class' => 'form', 'enctype' => 'multipart/form-data']) !!}
                            {!! Form::hidden('id',$user->id)!!}
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        {!! Form::text('first_name',old('first_name',$user->first_name),['class'=> 'form-control form-control-user', 'placeholder'=>'ชื่อจริง']) !!}
                                        {!! showError('first_name',$errors) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {!! Form::text('last_name',old('last_name',$user->last_name),['class'=> 'form-control form-control-user', 'placeholder'=>'นามสกุล']) !!}
                                        {!! showError('last_name',$errors) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        {!! Form::text('easy_name',old('easy_name',$user->easy_name),['class'=> 'form-control form-control-user', 'placeholder'=>'ชื่อเล่น']) !!}
                                        {!! showError('easy_name',$errors) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        {!! Form::text('mobile',old('mobile',$user->mobile),['class'=> 'form-control form-control-user', 'placeholder'=>'เบอร์โทรศัพท์']) !!}
                                        {!! showError('mobile',$errors) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <?php $is_actives = [""=>"-- กรุณาเลือกสถานะใช้งาน --" , "1"=>"เปิดใช้งาน" , "0"=>"ปิดใช้งาน"]; ?>
                                        {!! Form::select('is_active', $is_actives,old("name",$user->is_active), ['class'=> 'form-control form-control-user']) !!}
                                        {!! showError('is_active',$errors) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        {!! Form::select('level_id', $levels,old("name",$user->level_id), ['class'=> 'form-control form-control-user']) !!}
                                        {!! showError('level_id',$errors) !!}
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
    </script>
@endsection
