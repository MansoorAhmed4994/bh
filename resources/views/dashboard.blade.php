
@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">  
    <?php $total_orders=0;?>
        @foreach($data as $list)
            <div class="col-sm-4 form-group">  
                <div class="card" style="width: 18rem;">
                  <!--<img class="card-img-top" src="..." alt="Card image cap">-->
                  <div class="card-body">
                    <h5 class="card-title">{{$list->total}}</h5>
                    <p class="card-text">Status: {{$list->status}}</p>
                    
                    <p class="card-text">Amount: {{$list->amount}}</p>
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
                <h5 class="card-title"><?php echo $total_orders;?></h5>
                <p class="card-text">Total Orders</p>
                <!--<a href="{{route('ManualOrders.status.order.list','')}}" class="btn btn-primary">Go</a>-->
              </div> 
            </div>
        </div>
        
    </div>
</div>

@endsection
