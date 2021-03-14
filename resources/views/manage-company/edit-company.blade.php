@extends('layout.app')
@section('content')

    <div class="container">
        <div class = "row justify-content-center">
            <div class="col-xl-6 col-lg-6 col-sm-12">
                <div class="card shadow mb-3">
                {!! Form::open(['url'=>url("/manage-company/edit-company-save"), 'method', 'POST', 'class' => 'form', 'enctype' => 'multipart/form-data']) !!}
                    {!! Form::hidden('id',$company->id)!!}
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-secondary"> <i class="fas fa-radiation-alt"></i> แก้ไขข้อมูลร้านค้า</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="{{url('/storage/company/'.$company->path_image)}}" alt="">
                            <div class="form-group">
                                {!! Form::file('image',  ['class'=> 'file']) !!}
                                {!! showError('image',$errors) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::text('name',old('name',$company->name),['class'=> 'form-control form-control-user', 'placeholder'=>'ชื่อ...']) !!}
                            {!! showError('name',$errors) !!}
                        </div>
                        <div class="form-group ">
                            <?php $is_register = [""=>"-- กรุณาเลือกสถานะเปิดรับสมัครสมาชิก --" , "1"=>"เปิดรับสมัครสมาชิก" , "0"=>"ระงับการเปิดรับสมัครสมาชิก"]; ?>
                            {!! Form::select('is_register', $is_register, old('is_sell',$company->is_register), ['class'=> 'form-control form-control-user']) !!}
                            {!! showError('is_register',$errors) !!}
                        </div>
                        <div class="form-group ">
                            <?php $is_active = [""=>"-- กรุณาเลือกสถานะใช้งาน --" , "1"=>"เปิดการใช้งาน" , "0"=>"ระงับเปิดการใช้งาน"]; ?>
                            {!! Form::select('is_active', $is_active, old('is_active',$company->is_active), ['class'=> 'form-control form-control-user']) !!}
                            {!! showError('is_active',$errors) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::textarea('address',old('address',$company->address),['class'=> 'form-control form-control-user', 'placeholder'=>'ที่อยู่...']) !!}
                            {!! showError('address',$errors) !!}
                        </div>
                    </div>
                    {!! showError('field',$errors) !!}
                    <input type="submit"  class="btn btn-primary btn-user btn-block" value="บันทึก">
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    
@endsection
@section('script')
    <script type="text/javascript">

    </script>
@endsection
