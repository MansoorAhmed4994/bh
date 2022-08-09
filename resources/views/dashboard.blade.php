@extends('layouts.'.Auth::getDefaultDriver())

@section('content')

<div class="container">
    <div class="row">  
    <?php $total_orders=0;?>
        @foreach($data as $list)
            <div class="col-sm-4 form-group">  
                <div class="card" style="width: 18rem;">
                  <!--<img class="card-img-top" src="..." alt="Card image cap">-->
                  <div class="card-body">
                    <h3>{{$list->status}}</h3>
                    <h5 class="card-title">{{$list->total}}</h5> 
                    
                    <!--<p class="card-text">Amount: {{$list->amount}}</p>-->
                    <a href="{{route('ManualOrders.status.order.list',$list->status)}}" class="btn btn-primary">Go</a>
                  </div> 
                </div>
            </div>
            <?php $total_orders += $list->total; ?>
        @endforeach 
        
        <div class="col-sm-4 form-group">  
            <div class="card" style="width: 18rem;">
              <!--<img class="card-img-top" src="..." alt="Card image cap">-->
              <div class="card-body">
                  
                    <h3>Total Order</h3>
                <h5 class="card-title"><?php echo $total_orders;?></h5>
                
                <!--<a href="{{route('ManualOrders.status.order.list','')}}" class="btn btn-primary">Go</a>-->
              </div> 
            </div>
        </div>
        
    </div> 
    <div style="width: 600px; margin: auto;">
    <canvas id="myChart1"></canvas>
</div>



</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const labels = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
  ];

  const data = {
    labels: labels,
    datasets: [{
      label: 'My First dataset',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      data: [0, 10, 5, 2, 20, 30, 45],
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
