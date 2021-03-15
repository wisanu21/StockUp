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
                                    <th>ชื่อจริง</th>
                                    <th>นามสกุล</th>
                                    <th>ชื่อเล่น</th>
                                    <th>เบอร์โทร</th>
                                    <th>ร้านค้า</th>
                                    <th>สถานะใช้งาน</th>
                                    <th>ตำแหน่ง</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td>{{$employee->first_name}}</td>
                                            <td>{{$employee->last_name}}</td>
                                            <td>{{$employee->easy_name}}</td>
                                            <td>{{$employee->mobile}}</td>
                                            <td>{{$employee->Company->name}}</td>
                                            <td>
                                                @if($employee->is_active == 1)
                                                    เปิดใช้งาน
                                                @else
                                                    ปิดใช้งาน
                                                @endif    
                                            </td>
                                            <td>
                                                @if($employee->is_active == 1)
                                                    {{$employee->Level->name}}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <a  class="btn btn-primary btn-primary-blue btn-sm" style="margin-bottom: 1px;" onclick="changePassword({{$employee->id}})" ><i class="fas fa-key"></i></a>
                                                <a href="{{url( 'employee/edit/'.$employee->id )}}" class="btn btn-primary btn-primary-blue btn-sm" style="margin-bottom: 1px;"><i class="fas fa-edit"></i></a>
                                                <a class="btn btn-primary btn-primary-blue btn-sm " onclick="Delete({{$employee->id}})" style="margin-bottom: 1px;"><i class="fas fa-trash"></i></a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
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
                window.location.replace("{{url('employee/delete/')}}"+"/"+id);
            }   
        })
    }

    function changePassword(id){
        (async () => {
            const { value: formValues } = await Swal.fire({
                title: 'เปลี่ยนรหัสผ่าน',
                html:
                    // '<input id="old-password" class="swal2-input" type="password" placeholder="รหัสผ่านเดิม" >' +
                    '<input id="new-password" class="swal2-input" type="password" placeholder="รหัสผ่านใหม่">'+
                    '<input id="new-password2" class="swal2-input" type="password" placeholder="ยืนยันรหัสผ่านใหม่">',
                focusConfirm: false,
                showCancelButton: true,
                cancelButtonColor: '#d33',
                cancelButtonText: 'ยกเลิก',
                preConfirm: () => {
                    return {
                        // "old-password" : document.getElementById('old-password').value,
                        // "id-employee" : id,
                        "new-password" : document.getElementById('new-password').value,
                        "new-password2" : document.getElementById('new-password2').value
                    }
                }
            })

            if (formValues) {
                axios.post('/employee/change-password', {items: formValues , id : id})
                .then((response) => {
                    Swal.fire(
                        response.data.title,
                        response.data.detail,
                        response.data.status
                    ).then((result) => {
                        if(response.data.status != "success"){
                            if (result.isConfirmed) {
                                changePassword();
                            }
                        }
                    });

                })
            }
        })()
    }



    </script>
@endsection
