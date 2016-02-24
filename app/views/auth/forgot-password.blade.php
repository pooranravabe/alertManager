@extends('layouts.public')
@section('content')
<div class="container-fluid lost-password-wrapper">
    <div class="general-header-content text-center">
        <div class="col-lg-4 col-lg-offset-4 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">  
<!--            <div class="success-msg">
                <p>Please check your email for the password recovery link.</p>
            </div>-->
            <div class="row heading-content">
                <h3>Lost Your Password?</h3>
            </div>
        </div>
    </div>
    {{ Form::open(array('url' => '/forgot-password', 'id'=>'forgot-password-form')) }}  
    <div class="col-lg-4 col-lg-offset-4 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">   
        <div class="middle-content">
            <p>Please enter your email address. Check your inbox to find a link to
                create your new password.</p>
        </div>
        <div class="lost-email">                            
            <input type="email" name="email" class="form-control" id="email" placeholder="Email Address"  oninvalid="this.setCustomValidity('Email Name field is required')" oninput="setCustomValidity('')" required>                        
        </div>
        <div class="lost-submit">
            <button type="submit" class="btn btn-default btn-generic update">Submit</button>
        </div>
    </div>
    {{ Form::close() }}  
</div>
@endsection