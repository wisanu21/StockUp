@extends('layout.app')
@section('content')

    <link href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <div class="container">
        <div class = "row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-sm-12">
                <div class="card shadow mb-3">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-secondary"> {!! $menu->html_icon !!}  {!! $menu->name !!} </h6>
                    </div>
                    <div class="card-body">
                        <div class = "text-right" >
                            <a href="{{url('/promotion/add')}}" ><i class="fas fa-plus"></i> เพิ่มโปรโมชั่น</a>
                        </div>

                        <div class="text-center table-responsive">
                            <table class="table table-bordered" id="table-list">
                                <thead class="thead-light">
                                <tr>
                                    <th>ชื่อ</th>
                                    <th>จำนวนส่วนลด</th>
                                    <th>สถานะการใช้งานโปรโมชั่น</th>
                                    <th>โค้ดส่วนลด</th>
                                    <th>สถานะการใช้งานโค้ดส่วนลด</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($promotions as $promotion)
                                        <tr>
                                            <td>{{$promotion['name']}}</td>
                                            <td>
                                                @if($promotion['price_or_percentage'] == 'price')
                                                    {{$promotion['resource']." บาท"}}
                                                @else
                                                    {{$promotion['resource']." %"}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($promotion['is_active_promotion'] == 1)
                                                    {!! showIconStatus_y_or_n("y") !!} เปิดใช้งานโปรโมชั่น
                                                @else 
                                                    {!! showIconStatus_y_or_n("n") !!} ปิดใช้งานโปรโมชั่น
                                                @endif
                                            </td>
                                            <td>
                                                @if($promotion['code'] != null)
                                                    {{$promotion['code']}}
                                                @else 
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($promotion['is_active_code'] == 1)
                                                    {!! showIconStatus_y_or_n("y") !!} เปิดใช้งานโค้ด
                                                @else 
                                                    {!! showIconStatus_y_or_n("n") !!} ปิดใช้งานโค้ด
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <a href="{{url( 'promotion/detail/'.$promotion['id'] )}}" class="btn btn-primary btn-primary-blue btn-sm" style="margin-bottom: 1px;"><i class="fas fa-search"></i></a>
                                                    <a href="{{url( 'promotion/edit/'.$promotion['id'] )}}" class="btn btn-primary btn-primary-blue btn-sm" style="margin-bottom: 1px;"><i class="fas fa-edit"></i></a>
                                                    <a class="btn btn-primary btn-primary-blue btn-sm " onclick="Delete({{$promotion['id']}})" style="margin-bottom: 1px;"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
@section('script')
    <script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
    $(document).ready( function () {
        $('#table-list').DataTable();
    } );
function Delete(id) {
        // alert( "Handler for .click() called." );
        Swal.fire({
            title: 'คุณแน่ใจที่จะลบใช่ไหม?',
            text: "คุณจะไม่สามารถเปลี่ยนกลับได้!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่ต้องการลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.replace("{{url( 'promotion/delete/')}}"+"/"+id);
            }   
        })
    }


    </script>
@endsection
