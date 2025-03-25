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
     
        
        
        <div class="col-sm-10"> 
            
            <div class="col-sm-12"> 
            
                <!-- ==================================================================== -->
                <!-- ================== Graph Daily Performance Render ================== -->
                <!-- ==================================================================== --> 
                <div class="col-sm"> 
                    <div style="width: 100%;margin: auto;padding: 20px;">
                        <canvas id="DailyPerformanceChartId" style="max-height:500px;background: white;border-radius: 10px;padding: 20px;"></canvas>
                    </div>  
                </div> 
            
                <!-- ==================================================================== -->
                <!-- ================== Graph Daily Performance Render ================== -->
                <!-- ==================================================================== --> 
                
            </div>
            
            
            
            <div class="row col-sm-12">  
            
                <!-- ==================================================================== -->
                <!-- ========== Graph Calling Team Amount Performance Render ============ -->
                <!-- ==================================================================== --> 
                
                <div class="col-sm-6"> 
                    <div style="width: 100%;margin: auto;padding: 20px;">
                        <canvas id="CallingTeamPerformanceAmountBarChartId" style="max-height:500px;background: white;border-radius: 10px;padding: 20px;"></canvas>
                    </div>  
                </div> 
                <div class="col-sm-6"> 
                    <div style="width: 100%;margin: auto;padding: 20px;">
                        <canvas id="CallingTeamPerformanceOrdersBarChartId" style="max-height:500px;background: white;border-radius: 10px;padding: 20px;"></canvas>
                    </div>  
                </div>
                
            </div>
            
            
            <div class="row col-sm-12">  
                <!-- ==================================================================== -->
                <!-- ============ Graph Calling Team Orders Performance Render ========== -->
                <!-- ==================================================================== --> 
                
                <div class="col-sm"> 
                    <div style="width: 100%;margin: auto;padding: 20px;">
                        <canvas id="CallingTeamPerformanceAmountChartId" style="max-height:500px;background: white;border-radius: 10px;padding: 20px;"></canvas>
                    </div>  
                </div> 
                
                
                <div class="col-sm"> 
                    <div style="width: 100%;margin: auto;padding: 20px;">
                        <canvas id="CallingTeamPerformanceOrdersChartId" style="max-height:500px;background: white;border-radius: 10px;padding: 20px;"></canvas>
                    </div>  
                </div>
                
            </div> 
        </div>   
    </div>   
     
        


    <script>   
        // ==================================================================== 
        // ================== Graph Daily Performance Script ================== 
        // ==================================================================== 
        const daily_performance_chart_data = {
            backgroundColor: '#9BD0F5',
            labels: {!! json_encode($daily_performance_date) !!},
            datasets: [{
                backgroundColor: '#9BD0F5',
            label: 'My First Dataset',
            data: {!! json_encode($daily_performance_amount)!!},
            
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1, 
            }]
        }; 
        
        const daily_performance_chart_config = {
            type: 'line',
            data: daily_performance_chart_data,
            backgroundColor: 'white',
        }; 
        
        const DailyPerformanceChart = new Chart(
            document.getElementById('DailyPerformanceChartId'),
            daily_performance_chart_config
        );  
    
    
    
        // ==================================================================== 
        // ============= CALLING TEAM Amount Performance Script =============== 
        // ==================================================================== 
        const calling_team_performance_amount_chart_data = {
            backgroundColor: '#9BD0F5',
            labels: {!! json_encode($calling_team_performance_data->pluck('name')) !!},
            datasets: [{
                backgroundColor: '#9BD0F5',
            label: 'Amount Target',
            data: {!! json_encode($calling_team_performance_data->pluck('total_amount'))!!},
            backgroundColor: [ 
              'rgb(54, 162, 235)',
              'rgb(255, 205, 86)',
              'rgb(181 255 86)',
              'rgb(100 255 86)',
              'rgb(86 255 235)',
              'rgb(86 194 255)',
              'rgb(6 6 6)',
              'rgb(86 101 255)',
              'rgb(128 86 255)',
              'rgb(190 86 255)',
              'rgb(255 86 251)',
              'rgb(255 86 86)',
            ], 
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1, 
            }]
        }; 
        
        const calling_team_performance_amount_chart_config = {
            type: 'pie',
            data: calling_team_performance_amount_chart_data,
            backgroundColor: 'white',
        }; 
        
        const CallingTeamPerformanceAmountChart = new Chart(
            document.getElementById('CallingTeamPerformanceAmountChartId'),
            calling_team_performance_amount_chart_config
        ); 
        
        const calling_team_performance_amount_bar_chart_config = {
            type: 'bar',
            data: calling_team_performance_amount_chart_data,
            backgroundColor: 'white',
        }; 
        
        const CallingTeamPerformanceAmountBarChart = new Chart(
            document.getElementById('CallingTeamPerformanceAmountBarChartId'),
            calling_team_performance_amount_bar_chart_config
        );
    
    
    
        // ==================================================================== 
        // ============= CALLING TEAM Orders Performance Script dounut =============== 
        // ==================================================================== 
        const calling_team_performance_orders_chart_data = {
            backgroundColor: '#9BD0F5',
            labels: {!! json_encode($calling_team_performance_data->pluck('name')) !!},
            datasets: [{
                backgroundColor: '#9BD0F5',
            label: 'Orders Target',
            data: {!! json_encode($calling_team_performance_data->pluck('total_orders'))!!},
            backgroundColor: [ 
              'rgb(54, 162, 235)',
              'rgb(255, 205, 86)',
              'rgb(181 255 86)',
              'rgb(100 255 86)',
              'rgb(86 255 235)',
              'rgb(86 194 255)',
              'rgb(6 6 6)',
              'rgb(86 101 255)',
              'rgb(128 86 255)',
              'rgb(190 86 255)',
              'rgb(255 86 251)',
              'rgb(255 86 86)',
            ], 
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1, 
            }]
        }; 
        
        const calling_team_performance_orders_chart_config = {
            type: 'doughnut',
            data: calling_team_performance_orders_chart_data,
            backgroundColor: 'white',
        };
        
        const CallingTeamPerformanceOrdersDoughnutChart = new Chart(
            document.getElementById('CallingTeamPerformanceOrdersChartId'),
            calling_team_performance_orders_chart_config
        ); 
        
        const calling_team_performance_orders_bar_chart_config = {
            type: 'bar',
            data: calling_team_performance_orders_chart_data,
            backgroundColor: 'white',
        };
        
        const CallingTeamPerformanceOrdersBarChart = new Chart(
            document.getElementById('CallingTeamPerformanceOrdersBarChartId'),
            calling_team_performance_orders_bar_chart_config
        );
        
 
        
        
        
        
        
        
        
        
        
    </script>
@endsection
