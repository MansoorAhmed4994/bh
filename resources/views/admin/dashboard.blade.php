
@extends('layouts.'.Auth::getDefaultDriver()) 
@section('content') 

<?php 
    
    $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color1 = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
    $color2 = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
    
?>
<style>
    table
    {
        background: white;
        border-radius: 5px 5px;
        width: 100%;
    }
    th, td {
        border: 1px solid #f5f1f1;
        font-size: 18px;    
        text-align: left;
        padding: 0 10px;
    }
    .total_user_order_card
    {
        width: 100%;
        background: #8a16d9;
        color: white;
    } 
    .admin-dashboard-card-box-content
    {
        background: white;
        border-radius: 10px;
        padding:10px;
    }
    .admin-dashboard-card-box {
        padding: 15px;
    }
    </style>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script> 
<script type="text/javascript">
   
//====================barchart
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title:{
		text: "Top Ten Cities"
	},
	height:2000,
	backgroundColor:"white",
// 	backgroundColor:"rgb(0 0 0 / 0%)",
	axisY: {
		title: "Order Count",
		includeZero: true
// 		maximum: 100
	},
	legend: {
		cursor:"pointer",
		itemclick : toggleDataSeries
	},
	toolTip: {
		shared: true,
		content: toolTipFormatter
	},
	axisX: {
		interval: 1
	},
	axisY2: {
		interlacedColor: "rgba(1,77,101,.2)",
		gridColor: "rgba(1,77,101,.1)",
		title: "Number of Companies"
	},
	data: [
	    {
		type: "bar",
		showInLegend: true,
		name: "Gold",
		color: "gold",
		background:"rgb(0 0 0 / 0%)",
		dataPoints:{!! json_encode($shipment_cities_data) !!}
	}, 
// 	{
// 		type: "bar",
// 		showInLegend: true,
// 		name: "Bronze",
// 		color: "#A57164",
// 		dataPoints: [
// 		    {"y":"26","label":"Abbottabad"},
// 		    {"y":"2","label":"Alipur"},
// 		    {"y":"4","label":"Arifwala"},
// 		    {"y":"4","label":"Attock"}
// 		]
// 	}
	]
}); 
chart.render(); 

function handleChartHeight(chart){    
    var dpsWidth = 30;
    var plotAreaHeight = chart.axisX[0].bounds.height;
    var chartHeight = plotAreaHeight + (50 * dpsWidth);
    chart.options.dataPointWidth = dpsWidth;
    chart.options.height = chartHeight; 
}



// {!! json_encode($shipment_cities_data) !!}
function toolTipFormatter(e) {
	var str = "";
	var total = 0 ;
	var str3;
	var str2 ;
	for (var i = 0; i < e.entries.length; i++){
		var str1 = "<span style= \"color:"+e.entries[i].dataSeries.color + "\">" + e.entries[i].dataSeries.name + "</span>: <strong>"+  e.entries[i].dataPoint.y + "</strong> <br/>" ;
		total = e.entries[i].dataPoint.y + total;
		str = str.concat(str1);
	}
	str2 = "<strong>" + e.entries[0].dataPoint.label + "</strong> <br/>";
	str3 = "<span style = \"color:Tomato\">Total: </span><strong>" + total + "</strong><br/>";
	return (str2.concat(str)).concat(str3);
}

function toggleDataSeries(e) {
	if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else {
		e.dataSeries.visible = true;
	}
	chart.render();
}

}
</script>
<div class="col-sm-12">
    <div class="row">
        <form class="form-inline" method="post" action="{{ route('admin.dashboard.monthly') }}">
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
        <div class="col-sm-12"> 
            <h2>Order All Order Status</h2><hr>
        </div> 
    
    <?php $total_orders=0;$rows_division=0;?>
        @foreach($data as $list)
            <div class="col-sm form-group">  
                <div class="card" style="width: 100%;"> 
                  <div class="card-body status-{!! str_replace(' ', '-', $list->status) !!} dashbord-card-body">
                    <h5 class="card-title text-capitalize">{{$list->status}}: {{$list->total_orders}} <span class="float-right">Rs: {{(int)$list->total_amount}}</span></h5>
 
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th> 
                                <th>Orders</th>
                                <th>Qty</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list->users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <?php
                                        $tmp = \App\Models\User::find($user->id);
                                    ?>
                                    @if($tmp)
                                        <td>{{ $tmp->first_name}}</td>
                                    @else
                                        <td class="text-danger">No User Assigned</td>
                                    @endif
                                    <td>{{$user->total_orders}}</td>
                                    <td>{{$user->total_amount}}</td>
                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    
                    <form action="{{route('ManualOrders.index')}}" method="post" class="card-box-go-btn">
                        @csrf
                        <input type="hidden" name="order_status"  value="{{$list->status}}">
                        <input class="form-control mr-sm-2" type="hidden" name="date_to" id="date_to" value="{{$date_to}}">
                        <input class="form-control mr-sm-2" type="hidden" name="date_from" id="date_from" value="{{$date_from}}">
                        <button type="submit" class="btn btn-primary">Go</button>
                    </form>
                    
                    <!--<a href="{{route('ManualOrders.status.order.list',$list->status)}}" class="btn btn-primary">Go</a>-->
                  </div> 
                </div>
            </div>
            <?php $total_orders += $list->total_orders;$rows_division++ ?>
            
            
        @endforeach 
         
       
        
        <div class="col-sm-6 form-group">  
            <div class="card total_user_order_card"> 
              <div class="card-body">
         
                    <h3>Total Order</h3>
                    <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    
                                    <th>Orders</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users_totla_orders as $users_totla_order)
                                    <tr>
                                        <td>{{$users_totla_order->id}}</td>
                                        <?php
                                            $tmp = \App\Models\User::find($users_totla_order->id);
                                        ?>
                                        @if($tmp)
                                        <td>{{ $tmp->first_name}}</td>
                                        @else
                                        <td class="text-danger">No User Assigned</td>
                                        @endif
                                        <td>{{$users_totla_order->total_orders}}</td>
                                        <td>{{$users_totla_order->total_amount}}</td>
                                    </tr> 
                                @endforeach
                            </tbody>
                        </table>
                 
              </div> 
            </div>
        </div>
        
    </div>  
    <div class="row">  
        <div class="col-sm-12"> 
            <h2>Order Payment Status</h2><hr>
        </div>
    <?php 
    $total_trax_orders=0;
    $total_fare=0;
    $total_trax_amount=0;
    $tnet = 0;
    $tgross = 0;
    ?>
        @foreach($shipment as $list)
        <?php
            $color1 = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
            $color2 = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
        ?>
        
            <div class="col-sm-4 form-group">  
                <div class="card" style="width: 18rem;"> 
                  <div class="card-body" style="background:linear-gradient(45deg, <?=$color1?>, <?=$color2?>)">
                    <h3>{{$list->payment_status}}</h3>
                    <h5 class="card-title">{{$list->total}}</h5> 
                    <h6 class="card-title">Amount: {{$list->amount}}</h6> 
                    <h6 class="card-title">Fare: {{$list->fare}}</h6> 
                    <h5 class="card-title">Net Amount: {{($list->amount)-($list->fare)}}</h5>
                    
                    <a href="{{route('admin.accounts.shipment.status.list',[$list->payment_status,$date_from,$date_to])}}" class="btn btn-primary">Go</a>
                  </div> 
                </div>
            </div>
            
            <?php $total_fare += $list->fare; ?>
            <?php $total_trax_orders += $list->total; ?>
            <?php $total_trax_amount += $list->amount; ?>
        @endforeach 
        
        <div class="col-sm-4 form-group">  
            <div class="card" style="width: 18rem;"> 
              <div class="card-body">
                  
                    <h3>Total Order</h3>
                <h5 class="card-title"><?php echo $total_trax_orders;?></h5>
                <h5 class="card-title"><?php echo $total_trax_amount;?></h5>
                <h5 class="card-title"><?php echo $total_fare;?></h5>
                 
              </div> 
            </div>
        </div>
        
    </div>   
    <div class="row">  
        <div class="col-sm-12"> 
            <h2>Order Shipment Status</h2><hr>
        </div>
    <?php 
    $total_trax_orders=0;
    $tnet = 0;
    $tgross = 0;
    ?> 
        @foreach($shipmenttracking as $key => $shipmenttrackings) 
        
             
            
            <?php
                $color1 = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
                $color2 = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
            ?>
            
                <div class="col-sm-4 form-group">  
                    <div class="card" style="width: 18rem;"> 
                      <div class="card-body" style="background:linear-gradient(45deg, <?=$color1?>, <?=$color2?>)">
                        @for ($i = 0; $i < count($shipmenttrackings); $i++)
                            <h3>{{$key}}</h3>
                            <h5>Status: {{$shipmenttrackings[$i]->payment_status}}</h6>
                            <h6 class="card-title">Orders: {{$shipmenttrackings[$i]->total}}</h6> 
                            <h6 class="card-title">Amount: {{$shipmenttrackings[$i]->amount}}</h6> 
                            <?php $total_trax_orders +=$shipmenttrackings[$i]->total; ?>
                        @endfor
                        <a href="{{route('admin.accounts.tracking.status.list',[$key,$date_from,$date_to])}}" class="btn btn-primary">Go</a>
                      </div> 
                    </div>
                </div>
                
            
         @endforeach 
        
        
        <div class="col-sm-4 form-group">  
            <div class="card" style="width: 18rem;"> 
              <div class="card-body">
                  
                    <h3>Total Order</h3>
                <h5 class="card-title"><?php echo $total_trax_orders;?></h5>
                 
              </div> 
            </div>
        </div>
        
    </div> 
    
    
       
      
    
    
    
    <div class="row">
           
        <div class="col-sm-12"><h2>Inventory Detail</h2><hr></div>
        <?php $total_qty=0;?>
        <?php $total_cost = 0;?>   
        @for ($i = 0; $i < count($inventories); $i++)
            <div class="col-sm">   
                <div class="admin-dashboard-card-box">
                    <div class="admin-dashboard-card-box-content"> 
                        <h3>{{$inventories[$i]->stock_status}}</h3><hr>
                        <h6 class="card-title">Qty: {{$inventories[$i]->qty}}</h6> 
                        <h6 class="card-title">Cost: {{$inventories[$i]->cost}}</h6> 
                        <h6 class="card-title">Sale: {{$inventories[$i]->sale}}</h6> 
                        <?php $total_qty +=$inventories[$i]->qty; ?> 
                        <a href="{{route('inventory.index',[$inventories[$i]->stock_status,$date_from,$date_to])}}" class="btn btn-primary">Go</a>
                    </div> 
                </div>
            </div>
        @endfor 
        
        <div class="col-sm">
            <div class="admin-dashboard-card-box">
                <div class="admin-dashboard-card-box-content"> 
                    <h3>Remaining nventory</h3><hr>
                    <h5>Total Qty: {{$remaining_invertory[0]->total}}</h6>
                    <h6 class="card-title">Total Amount: {{$remaining_invertory[0]->amount}}</h6>  
                    <a href="" class="btn btn-primary">Go</a>
                </div> 
            </div>
        </div>
        
    </div>
    

</div> 
    
    <div class="row">
                <div class="col-sm-12"><h2>Inventory Detail</h2><hr></div>
        <div class="col-sm-4">
            <div class="admin-dashboard-card-box"> 
                <div class="admin-dashboard-card-box-content" style="height:2050px;">
                    <div id="chartContainer" style="border-radius: 18px;"></div>  
                </div>
            </div>
        </div>
        <div class="col-sm-8"> 
            <div class="row">
                <div class="col-sm-6">   
                    <div class="admin-dashboard-card-box">
                        <div class="admin-dashboard-card-box-content">
                            <canvas id="parcels_by_shipment_company"></canvas>
                        </div>
                    </div> 
                </div>
                
                <div class="col-sm-6">
                    <div class="admin-dashboard-card-box">
                        <div class="admin-dashboard-card-box-content">
                            <canvas id="myChart1"></canvas>
                        </div>
                    </div> 
                </div>
                
            </div>
        </div>
        
    </div> 
    

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
   
   
   
   
   
//Parcels delivered Cities Chart
  const labels = {!! json_encode($cities_name) !!}; 

  const data = {
    labels: labels,
    datasets: [{
      label: 'Local VS Other Cities',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',   
      data :{!! json_encode($total_city_orders)!!}, 

    }]
  };  

  const config = {
    type: 'line',
    data: data,
    options: {}
  };
  
  const myChart = new Chart(
    document.getElementById('myChart1'),
    config
  );
  
  
  //Parcels delivered Cities Chart
  @if(!empty($shipment_cities_summary['shipment_cities_name'])) 
  const labels2 = {!! json_encode($shipment_cities_summary['shipment_cities_name']) !!}; 
    @endif

  const data2 = {
    labels: labels2,
    datasets: [{
      label: 'Order Delivered in Cities',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      @if(!empty($shipment_cities_summary['shipment_cities_orders'])) 
        data :{!! json_encode($shipment_cities_summary['shipment_cities_orders'])!!},
      @endif
      

    }]
  };

  const config2 = {
    type: 'bar',
    data: data2,
    options: {}
  };
  
  const myChart2 = new Chart(
    document.getElementById('parcels_by_shipment_company'),
    config2
  ); 
  
  
 

  
  
  
  
  
  
  
  
  
  
  
  
  
  
</script>
@endsection
