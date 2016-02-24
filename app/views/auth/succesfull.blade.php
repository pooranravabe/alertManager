@extends('layouts.public')
@section('content')
<div class="container">    
    <div class="row login-wrapper">        
        <div class="col-lg-6 col-lg-offset-3 col-md-7 col-md-offset-1 col-sm-7 col-sm-offset-1">            
            <div class="success-form">                             
                    <div class="col-lg-12 text-center">
                        <img style="width:100%; padding:15px; max-width:100px;" src="{{URL::asset('assets/images/icon_smile.png')}}">
                    </div>
                    <div class="col-lg-12 text-center">
                        <h1>Sign Up Successful</h1>
                    </div>
                    <div class="col-lg-12 text-center">
                        <h4 class="success-message">A confirmation email is sent to your <span style="text-decoration: underline;">{{$email}}</span>,<br>please verify your email address.<br> Hop on board!</h4>
                    </div>
          </div>
          
        </div>         
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $(".loading").click(function() {
        var $btn = $(this);
        $btn.button('loading');
        setTimeout(function () {
            $btn.button('reset');
        }, 1000);
    });
});    
</script>
@endsection