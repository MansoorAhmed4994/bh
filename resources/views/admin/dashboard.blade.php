
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
</style>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script type="text/javascript">
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title:{
		text: "Olympic Medals of all Times (till 2016 Olympics)"
	},
	axisY: {
		title: "Medals",
		includeZero: true
	},
	legend: {
		cursor:"pointer",
		itemclick : toggleDataSeries
	},
	toolTip: {
		shared: true,
		content: toolTipFormatter
	},
	data: [{
		type: "bar",
		showInLegend: true,
		name: "Gold",
		color: "gold",
		dataPoints: [
			{ y: 243, label: "Italy" },
			{ y: 236, label: "China" },
			{ y: 243, label: "France" },
			{ y: 273, label: "Great Britain" },
			{ y: 269, label: "Germany" },
			{ y: 196, label: "Russia" },
			{ y: 1118, label: "USA" }
		]
	},
	{
		type: "bar",
		showInLegend: true,
		name: "Silver",
		color: "silver",
		dataPoints: [
			{ y: 212, label: "Italy" },
			{ y: 186, label: "China" },
			{ y: 272, label: "France" },
			{ y: 299, label: "Great Britain" },
			{ y: 270, label: "Germany" },
			{ y: 165, label: "Russia" },
			{ y: 896, label: "USA" }
		]
	},
	{
		type: "bar",
		showInLegend: true,
		name: "Bronze",
		color: "#A57164",
		dataPoints: [
			{ y: 236, label: "Italy" },
			{ y: 172, label: "China" },
			{ y: 309, label: "France" },
			{ y: 302, label: "Great Britain" },
			{ y: 285, label: "Germany" },
			{ y: 188, label: "Russia" },
			{ y: 788, label: "USA" }
		]
	}]
});
chart.render();

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
            <div class="col-sm-4 form-group">  
                <div class="card" style="width: 100%;"> 
                  <div class="card-body status-{!! str_replace(' ', '-', $list->status) !!} dashbord-card-body">
                    <h3>{{$list->status}}</h3>
                    <h5 class="card-title">{{$list->total_orders}}</h5> 
                    <h5 class="card-title">{{$list->total_amount}}</h5>  
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                
                                <th>Orders</th>
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
                                    <td>{{ $tmp->first_name}}</td>
                                    <td>{{$user->total_orders}}</td>
                                    <td>{{$user->total_amount}}</td>
                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <a href="{{route('ManualOrders.status.order.list',$list->status)}}" class="btn btn-primary">Go</a>
                  </div> 
                </div>
            </div>
            <?php $total_orders += $list->total_orders;$rows_division++ ?>
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
        <div class="col-sm-12"> 
            <h2>inventory</h2><hr>
        </div>
        <div class="col-sm-4 form-group">  
            <div class="card" style="width: 18rem;"> 
              <div class="card-body" style="background:linear-gradient(45deg, <?=$color1?>, <?=$color2?>)">
                    <h5>Total Qty: {{$remaining_invertory[0]->total}}</h6>
                    <h6 class="card-title">Total Amount: {{$remaining_invertory[0]->amount}}</h6> 
                
                <a href="" class="btn btn-primary">Go</a>
              </div> 
            </div>
        </div>
    </div>
    
    <div class="row">  
        <div class="col-sm-12"> 
            <h2>Order Shipment Status</h2><hr>
        </div>
        <?php 
            $total_qty=0;
            $total_cost = 0; 
        ?> 
            <?php
                $color1 = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
                $color2 = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
            ?>
            @for ($i = 0; $i < count($inventories); $i++)
            <div class="col-sm-4 form-group">  
                <div class="card" style="width: 18rem;"> 
                  <div class="card-body" style="background:linear-gradient(45deg, <?=$color1?>, <?=$color2?>)">
                    
                    <h5>Status: {{$inventories[$i]->stock_status}}</h6>
                        <h6 class="card-title">Qty: {{$inventories[$i]->qty}}</h6> 
                        <h6 class="card-title">Cost: {{$inventories[$i]->cost}}</h6> 
                        <h6 class="card-title">Sale: {{$inventories[$i]->sale}}</h6> 
                        <?php $total_qty +=$inventories[$i]->qty; ?>
                    
                    <a href="{{route('inventory.index',[$inventories[$i]->stock_status,$date_from,$date_to])}}" class="btn btn-primary">Go</a>
                  </div> 
                </div>
            </div>
            @endfor
                
            
        
        
        <div class="col-sm-4 form-group">  
            <div class="card" style="width: 18rem;"> 
              <div class="card-body">
                  
                    <h3>Total Order</h3>
                <h5 class="card-title"><?php echo $total_trax_orders;?></h5>
                 
              </div> 
            </div>
        </div>
        
    </div>  
    
    <div style="width: 100%; margin: auto;">
        <canvas id="myChart1"></canvas>
    </div>
    
    <div style="width: 100%; margin: auto;">
        <div id="chartContainer" style="height: 300px; width: 100%;"></div>
    </div>



</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
   
 
  const labels = {!! json_encode($cities_name) !!}; 

  const data = {
    labels: labels,
    datasets: [{
      label: 'Order Delivered in Cities',
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
  
 
</script>
<script>
  const myChart = new Chart(
    document.getElementById('myChart1'),
    config
  );
</script>
@endsection
