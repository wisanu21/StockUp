@extends('layout.app')
@section('content')

    <link href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/rowreorder/1.2.7/css/rowReorder.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css" rel="stylesheet" type="text/css">
    <div class="container">
        <div class = "row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-sm-12">
                <div class="card shadow mb-3">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-secondary"> {!! $menu->html_icon !!}  {!! $menu->name !!} </h6>
                    </div>
                    <div class="card-body">
                        <!-- <div class = "text-right" >
                            <a href="{{url('/manage-users/add')}}" ><i class="fas fa-plus"></i> เพิ่มผู้ใช้งานระบบ</a>
                        </div> -->

                        <div class="table-responsive">
                            <table class="display nowrap" style="width:100%" id="table-list">
                                <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th>ชื่อ</th>
                                    <th>สถานะการรับสมัครสมาชิก</th>
                                    <th>สถานะการใช้งาน</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($companies as $company)
                                        <tr>
                                            <td>
                                                <div style="
                                                    padding-top: 50%;
                                                    padding-bottom: 50%;
                                                    background: url({{url('/storage/company/'.$company->path_image)}});
                                                    background-repeat: no-repeat;
                                                    background-size: contain;
                                                    background-position:center;
                                                    background-color: #d1d1d1;
                                                    width: inherit;">
                                                </div>
                                            </td>
                                            <td>{{$company->name}}</td>
                                            <td>
                                                @if($company->is_register == 1)
                                                    {!! showIconStatus_y_or_n("y") !!} เปิดรับสมัคร
                                                @else
                                                    {!! showIconStatus_y_or_n("n") !!} ระงับเปิดรับสมัคร
                                                @endif
                                            </td>
                                            <td>
                                                @if($company->is_active == 1)
                                                    {!! showIconStatus_y_or_n("y") !!} เปิดใช้งาน
                                                @else
                                                    {!! showIconStatus_y_or_n("n") !!} ระงับใช้งาน
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{url( 'manage-company/edit-company/'.$company->id )}}" class="btn btn-primary btn-primary-blue btn-sm" style="margin-bottom: 1px;"><i class="fas fa-edit"></i></a>
                                                <a class="btn btn-primary btn-primary-blue btn-sm " onclick="Delete({{$company->id}})" style="margin-bottom: 1px;"><i class="fas fa-trash"></i></a>
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
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.7/js/dataTables.rowReorder.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript">
    $(document).ready( function () {
        $('#table-list').DataTable({
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            responsive: true
        });
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
                window.location.replace("{{url( 'manage-company/delete/')}}"+"/"+id);
            }   
        })
    }

    </script>
@endsection
