
@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('rider Dashboard') }}</div>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Consigment ID</th>
                            <th scope="col">Riders ID</th>
                            <th scope="col">Customer ID</th>
                            <th scope="col">Receiver Name</th>
                            <th scope="col">Receiver Number</th>
                            <th scope="col">Receiver Address</th>
                            <th scope="col">Delivery Destination</th>
                            <th scope="col">Total Pieces</th>
                            <th scope="col">Weight</th>
                            <th scope="col">Price</th>
                            <th scope="col">COD Amount</th>
                            <th scope="col">Advance Payment</th>
                            <th scope="col">Date Order Paid</th>
                            <th scope="col">Description</th>
                            <th scope="col">Reference Number</th>
                            <th scope="col">Service Type</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($lists as $list)
                        <tr>
                            <td>>{{$list->id}}</td>                  
                            <td>{{$list->consignment_id}}</td>
                            <td>{{$list->riders_id}}</td>
                            <td>{{$list->customers_id}}</td>
                            <td>{{$list->receiver_name}}</td>                  
                            <td>{{$list->receiver_number}}</td>
                            <td>{{$list->reciever_address}}</td>
                            <td>{{$list->order_delivery_location}}</td>
                            <td>{{$list->total_pieces}}</td>                  
                            <td>{{$list->weight}}</td>
                            <td>{{$list->price}}</td>
                            <td>{{$list->cod_amount}}</td>
                            <td>{{$list->advance_payment}}</td>                  
                            <td>{{$list->date_order_paid}}</td>
                            <td>{{$list->description}}</td>
                            <td>{{$list->reference_number}}</td>
                            <td>{{$list->service_type}}</td>                  
                            <td>{{$list->status}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection