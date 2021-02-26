@extends('layout.app')
@section('content')
    <!-- <div class="container-fluid">

    
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>

        </div>
    </div> -->

    <div class="container">
        <div class = "row justify-content-center">
            <div class="col-xl-6 col-lg-6 col-sm-12">
                <div class="card shadow mb-3">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-secondary"><i class="fas fa-store"></i> {{\Auth::user()->Company->name}}</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="{{url('/storage/company/'.\Auth::user()->Company->path_image)}}" alt="">
                        </div>
                        <p>ชื่อ : {{\Auth::user()->full_name}}</p>
                        <p>ตำแหน่ง : {!! \Auth::user()->Level->html_icon !!} {{\Auth::user()->Level->name}}</p>
                        <p>ชื่อเล่น : {{\Auth::user()->easy_name}}</p>
                        <p>เบอร์โทรศัพท์ : {{\Auth::user()->mobile}}</p>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
@section('script')
    <script type="text/javascript">

    </script>
@endsection
