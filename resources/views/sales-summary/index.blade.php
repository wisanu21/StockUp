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
                        {!! Form::open(['url'=>url("/sales-summary/index-post"), 'method', 'POST', 'class' => 'form', 'enctype' => 'multipart/form-data']) !!}
                            <div class="form-group row">
                            
                                <div class="col-sm-5">
                                    วันที่เริ่ม
                                    {!! Form::date('start_date', $start_date, ['class'=> 'form-control form-control-user']) !!}
                                </div>
                                <div class="col-sm-5">
                                    ถึง
                                    {!! Form::date('end_date', $end_date, ['class'=> 'form-control form-control-user']) !!}
                                </div>
                                <div class="col-sm-2">
                                    </br>
                                    <input type="submit"  class="btn btn-primary btn-user btn-block" value="ค้นหา">
                                </div>
                           
                            
                            </div>
                        {!! Form::close() !!}
                        <canvas id="myChart" height="100"></canvas>


                        <div class="table-responsive">
                            <table class="display nowrap" style="width:100%" id="table-list">
                                <thead class="thead-light">
                                <tr>
                                    <th>รหัส Order</th>
                                    <th>วันที่ทำรายการ</th>
                                    <th>ราคาสินค้าทั้งหมด (บาท) </th>
                                    <th>โปรโมชั่น (บาท) </th>
                                    <th>เงินที่ได้รับ (บาท) </th>
                                    <th>ทอนเงิน (บาท) </th>
                                    <th>เงินสุทธิ (บาท) </th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($arr_days as $date => $arr_day)
                                        @foreach($arr_day as $key => $order)
                                        <tr>
                                            <td>{{$order['name']}}</td>
                                            <td>{{$order['created_at']}}</td>
                                            <td>{{$order['sum_price']}}</td>
                                            <td>- {{$order['promotion_price']}}</td>
                                            <td>{{$order['get_money']}}</td>
                                            <td>{{$order['change_money']}}</td>
                                            <td>{{$order['final_price']}}</td>
                                            <td>
                                            <a href="{{url( 'sales-summary/detail/'.$order['id'] )}}" class="btn btn-primary btn-primary-blue btn-sm" style="margin-bottom: 1px;"><i class="fas fa-search"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
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

    </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.2/chart.min.js"></script>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');

    var labels = [] ;
    var data = [] ;
    @foreach($arr_days as $date => $arr_day)
        // console.log('{{$date}}');
        labels.push('{{$date}}');
        <?php $sum_final_price = 0 ; ?>
        @foreach($arr_day as $key => $order)
            <?php $sum_final_price = $sum_final_price + $order->final_price ?>
        @endforeach
        data.push('{{$sum_final_price}}');
    @endforeach
    console.log(labels , data);
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'ยอดต่อวัน (บาท) ',
                data: data,
                backgroundColor: [
                    // 'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    // 'rgba(255, 206, 86, 0.2)',
                    // 'rgba(75, 192, 192, 0.2)',
                    // 'rgba(153, 102, 255, 0.2)',
                    // 'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    // 'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    // 'rgba(255, 206, 86, 1)',
                    // 'rgba(75, 192, 192, 1)',
                    // 'rgba(153, 102, 255, 1)',
                    // 'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 0.3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection
