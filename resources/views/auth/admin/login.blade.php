
@extends('layouts.app')

@section('content') 
<div class="container" style="    margin-top: 12%;">
    <div class="row justify-content-center">
        <div class="col-md-5"> 
            <div class="card login-form">
                 <div class="col-md">
                    
                    <img src="{{ asset('public/images/logo.png')}}" style="    width: 50%;    margin: 20px 26%;    position: relative;"/> 
    
                    <div class="card-body">
                        @if(Session::has('flash_message_error'))
        
        
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>	
                                    <strong>{!! session('flash_message_error')!!}</strong>
                            </div>
        
                            @endif
        
                            @if(Session::has('flash_message_success'))
        
        
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>	
                                    <strong>{!! session('flash_message_success')!!}</strong>
                            </div>
        
                        @endif
                        <form method="POST" action="{{ route('admin.login') }}"> 
                            @csrf
    
                            <div class="form-group row justify-content-center"> 
                                <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" placeholder="{{ __('E-Mail Address') }}" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
                            </div>
    
                            <div class="form-group row justify-content-center"> 
                                <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
                            </div>
    
                            <div class="form-group row justify-content-center"> 
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div> 
                            </div>
    
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg col-sm-12">
                                    {{ __('Login') }}
                                </button> 
                            </div>
    
                            <div class="form-group row">  
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif 
                                <a class="btn btn-link" href="{{ route('login') }}">User </a>
                            </div> 
                        </form>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
