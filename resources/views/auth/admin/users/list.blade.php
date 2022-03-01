@extends('auth.admin.layouts.app')

@section('content')

    <div class="d-flex justify-content-center">
        
        <div class="d-flex justify-content-center">
            
            <table class="table">
                <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">First</th>
                      <th scope="col">Last</th>
                      <th scope="col">Handle</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                          <th scope="row">{{$user->id}}</th>
                          <td>{{$user->id}}</td> 
                          <td>{{$user->first_name}}</td>
                          <td>{{$user->email}}</td> 
                          <td><a href="{{route('admin.user.edit',$user->id)}}" class="btn btn-warning">Edit</a></td> 
                          <td><a href="button" class="btn btn-danger">Delete</a></td>  
                        </tr>
                    @endforeach
                </tbody>
            </table>
        
        </div>
        
    </div>
@endsection