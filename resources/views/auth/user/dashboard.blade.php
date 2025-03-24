@extends('layouts.'.Auth::getDefaultDriver())

@section('content')
 
    <div class="row"> 
     
        
        
<!--====================================================================-->
<!--================================================= Order Details-->
<!--==================================================================== -->
            <div class="col-sm-2">
                <?php $total_orders=0;?>
                @foreach($data as $list)  
                    <div class="col-sm form-group">  
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
                    
                    <?php $total_orders += $list->total_orders; ?>
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
     
        
        
<!--====================================================================-->
<!--================================================= Graph Daily Performance-->
<!--==================================================================== -->
            
            <div class="col-sm-10"> 
                <div style="width: 100%; margin: auto;">
                    <canvas id="DailyPerformanceChartId" style="max-height:500px"></canvas>
                </div>  
            </div>
            
              
    </div> 
     
        
        
<!--====================================================================-->
<!--================================================= Graph Daily Performance-->
<!--==================================================================== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script> 
//Parcels delivered Cities Chart
  const labels = {!! json_encode($daily_performance_date) !!}; 

  const data = {
    labels: labels,
    datasets: [{
      label: 'Local VS Other Cities',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',   
      data :{!! json_encode($daily_performance_amount)!!}, 

    }]
  };  

  const config = {
    type: 'line',
    data: data,
    options: {}
  };
  
  const myChart = new Chart(
    document.getElementById('DailyPerformanceChartId'),
    config
  );
</script>
@endsection
