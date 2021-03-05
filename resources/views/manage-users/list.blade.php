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
                        <!-- <div class = "text-right" >
                            <a href="{{url('/manage-users/add')}}" ><i class="fas fa-plus"></i> เพิ่มผู้ใช้งานระบบ</a>
                        </div> -->

                        <div class="text-center table-responsive">
                            <table class="table table-bordered" id="table-list">
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
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{$user->first_name}}</td>
                                            <td>{{$user->last_name}}</td>
                                            <td>{{$user->easy_name}}</td>
                                            <td>{{$user->mobile}}</td>
                                            <td>{{$user->Company->name}}</td>
                                            <td>
                                                @if($user->is_active == 1)
                                                    เปิดใช้งาน
                                                @else
                                                    ปิดใช้งาน
                                                @endif    
                                            </td>
                                            <td>
                                                @if($user->is_active == 1)
                                                    {{$user->Level->name}}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <a  class="btn btn-primary btn-primary-blue btn-sm" style="margin-bottom: 1px;" onclick="changePassword()" ><i class="fas fa-key"></i></a>
                                                <a href="{{url( 'manage-users/edit/'.$user->id )}}" class="btn btn-primary btn-primary-blue btn-sm" style="margin-bottom: 1px;"><i class="fas fa-edit"></i></a>
                                                <a class="btn btn-primary btn-primary-blue btn-sm " onclick="Delete({{$user->id}})" style="margin-bottom: 1px;"><i class="fas fa-trash"></i></a>
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
                window.location.replace("{{url( 'manage-users/delete/')}}"+"/"+id);
            }   
        })
    }

    function changePassword(){
        (async () => {
            const { value: formValues } = await Swal.fire({
            title: 'เปลี่ยนรหัสผ่าน',
            html:
                '<input id="old-password" class="swal2-input" type="password" placeholder="รหัสผ่านเดิม" >' +
                '<input id="new-password" class="swal2-input" type="password" placeholder="รหัสผ่านใหม่">'+
                '<input id="new-password2" class="swal2-input" type="password" placeholder="ยืนยันรหัสผ่านใหม่">',
            focusConfirm: false,
            showCancelButton: true,
            cancelButtonColor: '#d33',
            cancelButtonText: 'ยกเลิก',
            preConfirm: () => {
                return [
                document.getElementById('swal-input1').value,
                document.getElementById('swal-input2').value
                ]
            }
            })

            if (formValues) {
                Swal.fire(JSON.stringify(formValues))
            }
        })()
    }



    </script>
@endsection
