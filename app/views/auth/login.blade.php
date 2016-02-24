@extends('layouts.public')
@section('content')
<div class="container">    
    <div class="row login-wrapper">        
        <div class="col-lg-4 col-lg-offset-4 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">            
            <div class="floating-form"> 
                <div class="login-image">
                    <span>
                        <h4>LOG IN</h4>
                    </span> 
                </div>                
                <div class="floating-form-box">                    
                    {{ Form::open(array('url' => 'login', 'id'=>'login-form')) }}                     
                    <div class="form-group">                            
                        <input type="email" value="{{{ $cookie['email'] != ''? $cookie['email'] : ''}}}" name="email" class="form-control" id="email" placeholder="Email Address" oninvalid="InvalidEmail(this)" oninput="InvalidEmail(this)" required>                        
                    </div>                        
                    <div class="form-group">                            
                        <input type="password" value="" class="form-control" name="password" id="password" placeholder="password" oninvalid="this.setCustomValidity('Password field is required')" oninput="setCustomValidity('')" required>                        
                    </div>
                    <div class="remember-checkbox">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <label><input type="checkbox" name="remember">Remember my Email address</label>
                    </div>
                    <button class="btn btn-default btn-orange" type="submit">LOG IN</button>                        
                    {{ Form::close() }}                   
                    <div style="clear: both; height: 3px;"></div>                        
                    <div class="forget">                            
                        <a href="{{url('/forgot-password')}}">Lost your password?</a>                        
                    </div>                    
                    <div class="clearfix"></div>                
                </div>            
            </div>            
            <div class="member">                
                <p>Not a member? <a href="{{url('/sign-up')}}">Sign up today</a></p>            
            </div>        
        </div>         
    </div>
</div>
<script type="text/javascript">
function InvalidEmail(textbox) {
    if (textbox.value == '') {
        textbox.setCustomValidity('Email field is required');
    }
    else if (textbox.validity.typeMismatch){
        textbox.setCustomValidity('please type a valid email address');
    }
    else {
       textbox.setCustomValidity('');
    }
    return true;
}
</script>
@endsection