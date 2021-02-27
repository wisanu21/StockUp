@extends('layout.app')
@section('content')

    <link href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <div class="container">
        <div class = "row justify-content-center">
            <div class="col-xl-10 col-lg-10 col-sm-12">
                <div class="card shadow mb-3">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-secondary"> {!! $menu->html_icon !!}  {!! $menu->name !!} </h6>
                    </div>
                    <div class="card-body">
                        <div class = "text-right" >
                            <a href="{{url('/product/add')}}" ><i class="fas fa-plus"></i> เพิ่มสินค้า</a>
                        </div>

                        <div class="text-center table-responsive">
                            <table class="table table-bordered" id="table-list">
                                <thead class="thead-light">
                                <tr>
                                    <th>ชื่อ</th>
                                    <th>ราคา</th>
                                    <th>สถานะการขาย</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{$product['name']}}</td>
                                            <td>{{$product['price']}}</td>
                                            <td>
                                            @if($product['is_sell'])
                                                ขาย
                                            @else
                                                ระงับการขาย
                                            @endif    
                                            </td>
                                            <td>
                                                <a href="{{url( 'product/detail/'.$product['id'] )}}" class="btn btn-primary btn-primary-blue btn-sm"><i class="fas fa-search"></i></a>
                                                <a href="{{url( 'product/edit/'.$product['id'] )}}" class="btn btn-primary btn-primary-blue btn-sm"><i class="fas fa-edit"></i></a>
                                                <a href="{{url( 'product/delect/'.$product['id'] )}}" class="btn btn-primary btn-primary-blue btn-sm"><i class="fas fa-trash"></i></a>
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
    </script>
@endsection
