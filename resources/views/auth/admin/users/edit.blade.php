@extends('layouts.'.Auth::getDefaultDriver())

@section('content')
<div class="container">
    <form method="POST" action="{{ route('admin.user.update' , $user) }}">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Edit User') }}</div>

                <div class="card-body">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="first_name" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{$user->first_name}}" required autocomplete="first_name" autofocus>
                                

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="last_name" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{$user->last_name}}" required autocomplete="last_name" autofocus>

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{$user->phone}}" required autocomplete="phone" autofocus>

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{$user->address}}" required autocomplete="address" autofocus>

                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$user->email}}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value ="{{$user->password }}" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" value="{{$user->password}}" required autocomplete="new-password">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="role_id" class="col-md-4 col-form-label text-md-right">{{ __('Roll') }}</label>
                            <div class="col-md-6">
                                @foreach($roles as $role)
                                <div class="form-check">
                                    @if($user->roles->pluck('id')->contains($role->id)) 
                                        <input type="checkbox" name="roles[]" value="{{$role->id}}" checked>
                                    
                                    @else
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}">
                                    @endif
                                    <lable>{{ $role->name }}</lable>
                                </div>  
                                @endforeach
                            </div> 
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> 
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Page Permission</div>

                <div class="card-body">
                    <div class="form-group row">
                        
                        <div class="col-md-12">
                            @foreach($pages as $page)
                            <div class="form-check"> 
                            
                                <label for="role_id" class="col-md-4 col-form-label text-md-right">{{$page->name}}</label>
                                <input type="hidden" name="page_ids[]" value="{{$page->id}}">
                                @if($user->page_permission()->pluck('page_id')->contains($page->id)) 
                                    @if(isset($user->page_permission()->pluck('page_id','create')['yes']) == $page->id)
                                        <input type="checkbox" name="creates{{$page->id}}" value="{{$page->id}}" checked>
                                    @else
                                        <input type="checkbox" name="creates{{$page->id}}"  value="{{$page->id}}">
                                    @endif
                                    <lable>Create</lable>
                                    
                                    @if(isset($user->page_permission()->pluck('page_id','edit')['yes']) == $page->id)
                                        <input type="checkbox" name="edits{{$page->id}}" value="{{$page->id}}" checked>
                                    @else
                                        <input type="checkbox" name="edits{{$page->id}}" value="{{ $page->id }}">
                                    @endif
                                    <lable>Edit</lable>
                                    
                                    @if(isset($user->page_permission()->pluck('page_id','view')['yes']) == $page->id)
                                        <input type="checkbox" name="views{{$page->id}}" value="{{$page->id}}" checked>
                                    @else
                                        <input type="checkbox" name="views{{$page->id}}" value="{{ $page->id }}">
                                    @endif
                                    <lable>View</lable>
                                    
                                    @if(isset($user->page_permission()->pluck('page_id','delete')['yes']) == $page->id)
                                        <input type="checkbox" name="deletes{{$page->id}}" value="{{$page->id}}" checked>
                                    @else
                                        <input type="checkbox" name="deletess{{$page->id}}" value="{{ $page->id }}">
                                    @endif
                                    <lable>Delete</lable>
                                
                                @else 
                                        <input type="checkbox" name="creates{{$page->id}}"value="{{ $page->id }}">
                                        <lable>View</lable>
                                        
                                        <input type="checkbox" name="edits{{$page->id}}" value="{{ $page->id }}">
                                        <lable>edit</lable>
                                        
                                        <input type="checkbox" name="views{{$page->id}}" value="{{ $page->id }}">
                                        <lable>View</lable>
                                        
                                        <input type="checkbox" name="deletes{{$page->id}}" value="{{ $page->id }}">
                                        <lable>delete</lable>
                                @endif
                            </div>  
                            @endforeach
                        </div> 
                    </div>
                </div>
                
            </div>
        </div> 
    </form>
</div>
<script type="text/javascript"> 
    var base_url = '<?php echo e(url('/')); ?>';
        function update_permission(user_id,page_id,permission_type)
        {
            $("body").addClass("loading");  
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: base_url + '/admin/user/permisssions/'+user_id+'/'+page_id+'/'+permission_type,
                type: 'GET', 
                dataType: 'json',
                success: function(e)
                { 
                    alert(e.messege);
                    
                    $("body").removeClass("loading");
                },
                error: function(e) {alert(e); 
                    $("body").removeClass("loading");
                }
            });
            
        }
     
</script>
@endsection
