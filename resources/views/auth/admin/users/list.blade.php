@extends('layouts.'.Auth::getDefaultDriver())

@section('content')

    <div class="d-flex justify-content-center">
        
        <div class="d-flex justify-content-center">
            
            <table class="table">
                <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">First</th>
                      <th scope="col">Last</th>
                      <th scope="col">Roles</th>
                      @can ('edit-users')
                      <th scope="col">Handle</th>
                      @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                          <th scope="row">{{$user->id}}</th>
                          <td>{{$user->first_name}}</td>
                          <td>{{$user->email}}</td> 
                          <td>{{ implode(',',$user->roles()->get()->pluck('name')->toArray())}}</td> 

                          @can ('edit-users')

                          <td><a href="{{route('admin.user.edit',$user->id)}}" class="btn btn-warning">Edit</a></td> 

                          @endcan

                          @can ('delete-users')
                          <td>
                            <form action="{{ route('admin.user.destroy',$user)}}" method="POST">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" Class="btn btn-danger">Delete</button>
                            </form>
                                
                          </td>  
                          @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>
        
        </div>
        
    </div>
@endsection