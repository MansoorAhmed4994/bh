@extends('layouts.'.Auth::getDefaultDriver())

@section('content')

<div class="container">
    <div class="row">
        <form class="form-inline" method="post" action="{{ route('user.dashboard.search') }}">
            @csrf
            <div class="form-group">
                <input class="form-control mr-sm-2" type="date" name="date_from" id="date_from">
            </div>
            <div class="form-group">
                <input class="form-control mr-sm-2" type="date" name="date_to" id="date_to"  >
            </div>
            <button type="submit">Search</button>
        </form>
    </div>
    
    <div class="row">  
    <?php $total_orders=0;?>
        @foreach($data as $list)
            <div class="col-sm-4 form-group">  
                <div class="card" style="width: 18rem;"> 
                  <div class="card-body">
                    <h3>{{$list->status}}</h3>
                    <h5 class="card-title">{{$list->total}}asd</h5>  
                    <a href="{{route('ManualOrders.status.order.list',$list->status)}}" class="btn btn-primary">Go</a>
                  </div> 
                </div>
            </div>
            <?php $total_orders += $list->total; ?>
        @endforeach 
        
        <div class="col-sm-4 form-group">  
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
  const myChart = new Chart(
    document.getElementById('myChart1'),
    config
  );
</script>
@endsection
