@extends('layouts.'.Auth::getDefaultDriver())

@section('content')

<div class="container">
    <div class="row">   
        <!--<table class="table">-->
        <!--    <thead>-->
        <!--        <tr>-->
        <!--            <th scope="col">Status</th>-->
        <!--            <th scope="col">Order</th>-->
        <!--            <th scope="col">Amount</th>-->
        <!--            <th scope="col">Action</th>-->
        <!--        </tr>-->
        <!--    </thead>-->
        <!--    <tbody>-->
                <?php $total_orders=0;?>
                @foreach($data as $list) 
                
                    <!--<tr class="status-{!! str_replace(' ', '-', $list->status) !!} ">-->
                    <!--    <th scope="row">{{$list->status}}</th>-->
                    <!--    <td>{{$list->total_orders}}</td>-->
                    <!--    <td>{{$list->total_amount}}</td>-->
                    <!--    <td><a href="{{route('ManualOrders.status.order.list',$list->status)}}" class="btn btn-primary">Go</a></td> -->
                    <!--    <form action="{{route('ManualOrders.index')}}" method="post">-->
                    <!--        @csrf-->
                    <!--        <input type="hidden" name="order_status"  value="{{$list->status}}"> -->
                    <!--    </form>-->
                    <!--</tr> -->
                    
                    
                    <div class="col-sm-4 form-group">  
                            <div class="card" style="width: 100%;"> 
                              <div class="card-body status-{!! str_replace(' ', '-', $list->status) !!} dashbord-card-body">
                                <h3>{{$list->status}}</h3>
                                <h5 class="card-title">{{$list->total_orders}}</h5> 
                                <h5 class="card-title">{{$list->total_amount}}</h5>  
                                <br>
                    
                                <form action="{{route('ManualOrders.index')}}" method="post" >
                                    @csrf
                                    <input type="hidden" name="order_status"  value="{{$list->status}}">
                                    <input class="form-control mr-sm-2" type="hidden" name="date_to" id="date_to" value="{{$date_to}}">
                                    <input class="form-control mr-sm-2" type="hidden" name="date_from" id="date_from" value="{{$date_from}}">
                                    <button type="submit" class="btn btn-primary">Go</button>
                                </form>
                              </div> 
                            </div>
                        </div>
            
            <?php $total_orders += $list->total; ?>
        @endforeach 
        
        <div class="col-sm-3 form-group">  
            <div class="card" style="width: 18rem;"> 
              <div class="card-body">
                  
                    <h3>Total Order</h3>
                <h5 class="card-title"><?php echo $total_orders;?></h5>
                 
              </div> 
            </div>
        </div>
        
    </div> 
    <div style="width: 100%; margin: auto;">
        <canvas id="myChart1"></canvas>
    </div>



</div> 
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
//   const labels = [
//     'January',
//     'February',
//     'March',
//     'April',
//     'May',
//     'June',
//   ];
  
  const labels = {!! json_encode($cities_name) !!};
  

  const data = {
    labels: labels,
    datasets: [{
      label: 'Order Delivered in Cities',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
    //   data: [0, 10, 5, 2, 20, 30, 45],
      data :{!! json_encode($total_city_orders)!!},
    }]
  };

  const config = {
    type: 'line',
    data: data,
    options: {}
  };
</script>
<script>
  
//   const myChart = new Chart(
//     document.getElementById('myChart1'),
//     config
//   );
  
</script>
@endsection
