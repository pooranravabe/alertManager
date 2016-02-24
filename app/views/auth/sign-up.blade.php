@extends('layouts.public')
@section('content')
{{HTML::script('assets/js/password-strength.js')}}
<script type="text/javascript">
    $(document).ready(function () {
    $('#password').pwstrength();    
    $('.rule').hide();
    $('#password').focus(function(event){
        $('.rule').show();
    });
    $('#password').focusout(function(event){
        $('.rule').hide();
    });
    $('.show').click(function(){
        var type = $('#password').attr('type');
        var check = $('#password').val();
        if(type == 'password'){
            $('#password').attr('type', 'text');
            $(this).find('i').attr('class', 'fa fa-eye-slash');
        }else{
            $('#password').attr('type', 'password');
            $(this).find('i').attr('class', 'fa fa-eye');
        }
    });
    $('#signup-form').bind('submit', function(){
        var network_name = $('#time-zone').val();
        if(!network_name){
            toastr.clear(toast); 
            var toast = toastr.error('Please Choose your Time Zone.');
            return false;
        }
    });
    });
</script>
<style type="text/css">
 .login-wrapper .strengthmeter{ 
    width:50%;float:left;margin-top:5px
  }
 .login-wrapper .password-verdict{
    margin-top:5px;display:inline-block
 }
 #time-zone{max-width: 100%;
    width:100%;}
</style>
<div class="container">    
    <div class="row login-wrapper">        
        <div class="col-lg-6 col-lg-offset-3 col-md-4 col-md-offset-2 col-sm-4 col-sm-offset-2">            
            <div class="floating-form">                
                <h4>SIGN UP</h4>                
                <div class="floating-form-box">                    
                    {{ Form::open(array('url' => '/create-user', 'id'=>'signup-form')) }}       
                        <div class="form-group">                            
                            <input type="text" placeholder="First Name" name="firstname" class="form-control" pattern="^[a-z \A-Z \u4E00-\u9FA5\uF900-\uFA2D]{2,20}$" oninvalid="this.setCustomValidity('First Name should be alphabatical and must be at least 2 characters long')" oninput="setCustomValidity('')" required>                        
                        </div>                       
                        <div class="form-group">                            
                            <input type="text" placeholder="Last Name" name="lastname" class="form-control"  pattern="^[a-z \A-Z \u4E00-\u9FA5\uF900-\uFA2D]{1,20}$" oninvalid="this.setCustomValidity('Last Name should be alphabatical and must be at least 1 characters long')" oninput="setCustomValidity('')" required>                        
                        </div> 
                        <div class="form-group">                            
                            <input type="email" name="email" pattern="[a-zA-Z0-9._\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,4}" placeholder="Email address" class="form-control" id="email"  oninvalid="InvalidEmail(this)" oninput="InvalidEmail(this)" required>                        
                        </div>
                        <div class="form-group">                            
                            <select name='user_tz' id="time-zone">
                                <option value="0" disabled selected="selected">Choose your timezone</option>
                                @foreach ($timezone_details as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>                           
                        </div>
                     
                        <div class="form-group text-input"> 
                            <input type="password" placeholder="Password" class="form-control" name="password" id="password" title="Password must contain at least 8 characters, including UPPER/lowercase and numbers" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,30}" onchange="this.setCustomValidity(this.validity.patternMismatch ? this.title : '');" data-toggle="password" required>
                            <p class="show"><i class="fa fa-eye"></i></p> 
                            <div class="form-group text-input rule">
                                <img src="{{URL::asset('assets/images/angle.png')}}">
                                <ul class="password-rule">
                                    <li>Password must contain 8 characters</li>
                                    <li>Password must include uppercase characters</li>
                                    <li>Password must include numbers</li>
                                </ul>
                            </div> 
                        </div>
                         <div class="clearfix"></div>
                        
                        <div class="clearfix"></div>
                        <div class="remember-checkbox">
                            <p><input type="checkbox" name="remember" onchange="this.setCustomValidity(validity.valueMissing ? 'Please agree to the Terms and Conditions' : '');" required>I agree to the <a id="terms" href="javascript:void(0)">Terms and conditions.</a></p>
                        </div>   
                    <input class="btn btn-default btn-orange-signup" id="sign_up" type="submit" value="SIGN UP">                        
                    <div style="clear: both; height: 3px;"></div>                        
                    <div class="forget">                          
                        <!-- <p>By clicking 'sign up' I agree to the </p>                       -->
                    </div>                    
                    {{ Form::close() }}                      
                    <div class="clearfix"></div>                
                </div>            
            </div>            
            <div class="member">                
                <p>Already a member? <a href="{{url('/')}}">Login</a></p>            
            </div>        
        </div>         
    </div>
</div>
<script> 
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
<style type="text/css">
.form-group .heapBox .heap{
    width: 473px;
}
.form-group .heapBox .handler{
    width: 5%;
    z-index: 1;
}
.form-group .heapBox{
    width: 473px;
}
.form-group .heapBox .holder{
    width: 445px;
    font-size: 16px;
    text-indent: 5px;
    z-index: 1;

}
.form-group .heapBox .heap .heapOptions .heapOption a{
    font-size:13px;
    text-indent:5px;
}
</style>
@endsection