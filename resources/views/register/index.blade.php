<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Login</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="{{asset('css/sb-admin-2.min.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.15.5/sweetalert2.min.css" rel="stylesheet" type="text/css">

</head>

<body class="bg-gradient-primary">

    <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">สมัครสมาชิก</h1>
                            </div>
                            {!! Form::open(['url'=>url("/register"), 'method', 'POST', 'class' => 'form', 'enctype' => 'multipart/form-data']) !!}
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        {!! Form::text('first_name',old('first_name'),['class'=> 'form-control form-control-user', 'placeholder'=>'ชื่อจริง']) !!}
                                        {!! showError('first_name',$errors) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        {!! Form::text('last_name',old('last_name'),['class'=> 'form-control form-control-user', 'placeholder'=>'นามสกุล']) !!}
                                        {!! showError('last_name',$errors) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        {!! Form::select('company', $companies, old('company'), ['class'=> 'form-control form-control-user company']) !!}
                                        <div class="text-right">
                                            <a class="small" href="{{url('/register-company')}}">ต้องการสร้างร้านค้าหรือไม่ ?</a>
                                        </div>
                                        {!! showError('company',$errors) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        {!! Form::text('easy_name',old('easy_name'),['class'=> 'form-control form-control-user', 'placeholder'=>'ชื่อเล่น']) !!}
                                        {!! showError('easy_name',$errors) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        {!! Form::text('mobile',old('mobile'),['class'=> 'form-control form-control-user', 'placeholder'=>'เบอร์โทรศัพท์']) !!}
                                        {!! showError('mobile',$errors) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="exampleInputPassword" name = "password" placeholder="รหัสผ่าน" value = "{{old('password')}}">
                                            {!! showError('password',$errors) !!}
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="exampleRepeatPassword" name = "repeat_password" placeholder="ยืนยันรหัสผ่าน" value = "{{old('repeat_password')}}">
                                            {!! showError('repeat_password',$errors) !!}
                                    </div>
                                </div>
                                {!! showError('field',$errors) !!}
                                <input type="submit"  class="btn btn-primary btn-user btn-block" value="บันทึก">
                                <a class="btn btn-secondary btn-user btn-block  " href="{{url('')}}" >ย้อนกลับ </a>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>

    <script>
    // $("select [value='']").attr("selected", "selected");
    $("select [value='']").attr("disabled", "disabled");
    </script>
    @if (session('response'))
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.15.5/sweetalert2.min.js"></script>
        <script>
            Swal.fire(
                "{{ session('response')['title'] }}",
                "{{ session('response')['detail'] }}",
                "{{ session('response')['status'] }}"
            )
        </script>
    @endif
</body>

</html>

