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
                            <h3>{{$list->total_orders}} {{$list->status}}</h3> 
                            <h5 class="card-title">{{$list->total_amount}}</h5>   
                
                            <form action="{{route('ManualOrders.index')}}" method="post" >
                                @csrf
                                <input type="hidden" name="order_status"  value="{{$list->status}}">
                                <input class="form-control mr-sm-2" type="hidden" name="date_to" id="date_to" value="{{$date_to}}">
                                <input class="form-control mr-sm-2" type="hidden" name="date_from" id="date_from" value="{{$date_from}}">
                                <button type="submit" class="btn btn-primary">View</button>
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
            <!-- ==================================================================== -->
            <!-- ================== Calling Team Employees list ================== -->
            <!-- ==================================================================== --> 
            @php
                function generateLightColor() {
                    $r = rand(150, 255);
                    $g = rand(150, 255);
                    $b = rand(150, 255);
                    return sprintf("#%02X%02X%02X", $r, $g, $b);
                }
            @endphp
            <div class="row col-sm-12">
                 
                <div class="d-flex"> 
                 
                    <div class="d-flex justify-content-start"> 
                        <div class="btn-toolbar " role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group mr-2" role="group" aria-label="First group">
                                @if($calling_team_list != '')
                                    <?php $i=0;?>
                                    @foreach ($calling_team_list as $employee)
                                        @php $color = generateLightColor(); @endphp
                                        <button type="button" class="btn" style="color:black;background-color: {{ $color }};"  onclick="addEmployeeDailyAchievedData({{$employee->id}},'{{$employee->name}}',{{$i}},'{{$date_from}}','{{$date_to}}');">{{ $employee->name }}</button>
                                            
                                        </button> 
                                        <?php $i++;?>
                                    @endforeach 
                                @endif 
                            
                            </div> 
                        </div>
                    </div> 
                    
                    <div class="d-flex justify-content-end">
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
                     
                </div> 
            </div>
            
       
            <!-- ==================================================================== -->
            <!-- =================== Graph Daily achieved Render ==================== -->
            <!-- ==================================================================== --> 
            <div class="row col-sm-12" style="padding-bottom: 20px;">  
                <canvas id="DailyachievedChartId" class="chartjs-container"></canvas>
                
            </div>
            
            
            
            <!-- ==================================================================== -->
            <!-- ========== Graph Calling Team Amount achieved Render ============ -->
            <!-- ==================================================================== -->  
            <div class="row col-sm-12" style="padding-bottom: 20px;">  
                
                <div class="col-sm" style="margin-left: -15px;">  
                    <canvas id="CallingTeamAchievedAmountBarChartId" class="chartjs-container"></canvas> 
                </div>
                
                <div class="row col-sm">  
                    <canvas id="CallingTeamAchievedOrdersBarChartId" class="chartjs-container"></canvas> 
                </div>
                
            </div>
            
            
            <!-- ==================================================================== -->
            <!-- ============ Graph Calling Team Orders achieved Render ========== -->
            <!-- ==================================================================== --> 
            <div class="row col-sm-12">  
                <div class=" col-sm" style="margin-left: -15px;">  
                    <canvas id="CallingTeamAchievedAmountPieChartId" class="chartjs-container"></canvas>   
                </div> 
                
                
                <div class="row col-sm">  
                    <canvas id="CallingTeamAchievedOrdersPieChartId" class="chartjs-container"  ></canvas> 
                </div>
                
            </div> 
        </div>   
    </div>   
     
        


    <script>   
    
    function addEmployeeDailyAchievedData(Employee_id,emp_name,c_index,d_from,d_to)
    {  
        $("body").addClass("loading")
        $.ajax({
            url: "{{ route('user.dashboard.GetDailyCallingTeamDispatchData') }}",
            type: "POST",
            data: {
                id:Employee_id,
                emp_name:emp_name,
                color_index:c_index,
                from_date:d_from,
                to_date:d_to
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response.data);
                daily_achieved_chart_data.datasets.push(response.data); 
                DailyachievedChart.update(); 
                $("body").removeClass("loading");
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        }); 
        
        // var  data1 =    
        //         {
        //             data:  ['449570.00',  0, '149410.00', '320210.00', '202820.00', '228650.00', '142320.00', '163810.00', '373920.00', '106820.00', '185060.00', '229930.00', '125630.00', '222635.00', '174120.00', '232750.00', '303910.00', '46860.00', '119870.00', '210045.00', '131110.00', '90390.00', '62520.00', '77330.00', '57850.00', '94160.00'],
        //             label: "test",
        //             fill: "false",
        //             tension: "0.1",
        //             backgroundColor:"#f75f5f"
        //         };
        // daily_achieved_chart_data.datasets.push(data1); 
        // console.log(daily_achieved_chart_data);
        // DailyachievedChart.update(); 
        
    } 
    
    function getdatadb()
    {
        alert('s');
    }
          
          
        //  console.log(@json($TeamDailyPerformance));
        // ==================================================================== 
        // ================== Graph Daily achieved Script ================== 
        // ==================================================================== 
        const daily_achieved_chart_data = @json($TeamDailyPerformance);  
        const daily_achieved_chart_config = {
            type: 'line',
            data: daily_achieved_chart_data,
            options: { 
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                stacked: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Team Daily Performance'
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        
                        // grid line settings
                        grid: {
                          drawOnChartArea: false, // only want the grid lines for one axis to show up
                        },
                    },
                }
            },
            backgroundColor: 'white',
        }; 
        
        const DailyachievedChart = new Chart(
            document.getElementById('DailyachievedChartId'),
            daily_achieved_chart_config
        );  
    
     
        // ==================================================================== 
        // ============= CALLING TEAM achieved Amount  Script =================
        // ==================================================================== 
        const calling_team_achieved_amount_chart_data = {
            backgroundColor: '#9BD0F5',
            labels: {!! json_encode($calling_team_achieved_data->pluck('name')) !!},
            datasets: [{
                backgroundColor: '#9BD0F5',
            label: 'Amount Target',
            data: {!! json_encode($calling_team_achieved_data->pluck('total_amount'))!!},
            fill: true,
            borderWidth: 1,
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',   // Red
                'rgba(255, 159, 64, 0.5)',   // Orange
                'rgba(255, 205, 86, 0.5)',   // Yellow
                'rgba(75, 192, 192, 0.5)',   // Teal
                'rgba(54, 162, 235, 0.5)',   // Blue
                'rgba(153, 102, 255, 0.5)',  // Purple
                'rgba(201, 203, 207, 0.5)',  // Gray
            
                // Added more attractive colors
                'rgba(0, 255, 127, 0.5)',    // Spring Green
                'rgba(255, 140, 0, 0.5)',    // Dark Orange
                'rgba(220, 20, 60, 0.5)',    // Crimson
                'rgba(0, 191, 255, 0.5)',    // Deep Sky Blue
                'rgba(186, 85, 211, 0.5)',   // Medium Orchid
                'rgba(72, 61, 139, 0.5)',    // Dark Slate Blue
                'rgba(173, 255, 47, 0.5)',   // Green Yellow
                'rgba(250, 128, 114, 0.5)'   // Salmon
            ], 
            borderColor: [
                'rgb(255, 0, 0)',      // Strong Red for contrast
                'rgb(204, 85, 0)',     // Deep Orange
                'rgb(204, 153, 0)',    // Dark Yellow
                'rgb(0, 128, 128)',    // Dark Teal
                'rgb(0, 77, 153)',     // Dark Blue
                'rgb(102, 0, 204)',    // Deep Purple
                'rgb(128, 128, 128)',  // Dark Gray
                
                // Contrasting borders for additional colors
                'rgb(0, 128, 64)',     // Dark Green
                'rgb(153, 76, 0)',     // Deep Orange-Brown
                'rgb(139, 0, 0)',      // Dark Red
                'rgb(0, 0, 153)',      // Deep Navy Blue
                'rgb(102, 0, 153)',    // Deep Violet
                'rgb(47, 0, 77)',      // Dark Purple
                'rgb(102, 153, 0)',    // Olive Green
                'rgb(153, 51, 51)'     // Dark Salmon
            ],  
            tension: 0.1, 
            }]
        }; 
        
        const calling_team_achieved_amount_chart_config = {
            type: 'pie',
            data: calling_team_achieved_amount_chart_data,
            backgroundColor: 'white',
        }; 
        
        const CallingTeamachievedAmountChart = new Chart(
            document.getElementById('CallingTeamAchievedAmountPieChartId'),
            calling_team_achieved_amount_chart_config
        ); 
        
        const calling_team_achieved_amount_bar_chart_config = {
            type: 'bar',
            data: calling_team_achieved_amount_chart_data,
            backgroundColor: 'white',
        }; 
        
        const CallingTeamachievedAmountBarChart = new Chart(
            document.getElementById('CallingTeamAchievedAmountBarChartId'),
            calling_team_achieved_amount_bar_chart_config
        );
    
    
    
        // ==================================================================== 
        // ============= CALLING TEAM Orders achieved Script dounut =============== 
        // ==================================================================== 
        const calling_team_achieved_orders_chart_data = {
            backgroundColor: '#9BD0F5',
            labels: {!! json_encode($calling_team_achieved_data->pluck('name')) !!},
            datasets: [{
                backgroundColor: '#9BD0F5',
            label: 'Orders Target',
            data: {!! json_encode($calling_team_achieved_data->pluck('total_orders'))!!},
            fill: false,
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',   // Red
                'rgba(255, 159, 64, 0.5)',   // Orange
                'rgba(255, 205, 86, 0.5)',   // Yellow
                'rgba(75, 192, 192, 0.5)',   // Teal
                'rgba(54, 162, 235, 0.5)',   // Blue
                'rgba(153, 102, 255, 0.5)',  // Purple
                'rgba(201, 203, 207, 0.5)',  // Gray
            
                // Added more attractive colors
                'rgba(0, 255, 127, 0.5)',    // Spring Green
                'rgba(255, 140, 0, 0.5)',    // Dark Orange
                'rgba(220, 20, 60, 0.5)',    // Crimson
                'rgba(0, 191, 255, 0.5)',    // Deep Sky Blue
                'rgba(186, 85, 211, 0.5)',   // Medium Orchid
                'rgba(72, 61, 139, 0.5)',    // Dark Slate Blue
                'rgba(173, 255, 47, 0.5)',   // Green Yellow
                'rgba(250, 128, 114, 0.5)'   // Salmon
            ], 
            borderColor: [
                'rgb(255, 0, 0)',      // Strong Red for contrast
                'rgb(204, 85, 0)',     // Deep Orange
                'rgb(204, 153, 0)',    // Dark Yellow
                'rgb(0, 128, 128)',    // Dark Teal
                'rgb(0, 77, 153)',     // Dark Blue
                'rgb(102, 0, 204)',    // Deep Purple
                'rgb(128, 128, 128)',  // Dark Gray
                
                // Contrasting borders for additional colors
                'rgb(0, 128, 64)',     // Dark Green
                'rgb(153, 76, 0)',     // Deep Orange-Brown
                'rgb(139, 0, 0)',      // Dark Red
                'rgb(0, 0, 153)',      // Deep Navy Blue
                'rgb(102, 0, 153)',    // Deep Violet
                'rgb(47, 0, 77)',      // Dark Purple
                'rgb(102, 153, 0)',    // Olive Green
                'rgb(153, 51, 51)'     // Dark Salmon
            ], 
            tension: 0.1,
            borderWidth: 1, 
            }]
        }; 
        
        const calling_team_achieved_orders_chart_config = {
            type: 'doughnut',
            data: calling_team_achieved_orders_chart_data,
            backgroundColor: 'white',
        };
        
        const CallingTeamachievedOrdersDoughnutChart = new Chart(
            document.getElementById('CallingTeamAchievedOrdersPieChartId'),
            calling_team_achieved_orders_chart_config
        ); 
        
        const calling_team_achieved_orders_bar_chart_config = {
            type: 'bar',
            data: calling_team_achieved_orders_chart_data,
            backgroundColor: 'white',
        };
        
        const CallingTeamachievedOrdersBarChart = new Chart(
            document.getElementById('CallingTeamAchievedOrdersBarChartId'),
            calling_team_achieved_orders_bar_chart_config
        );
        
 
        
        
        
        
        
        
        
        
        
    </script>
@endsection
