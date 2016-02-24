@extends('layouts.public')
@section('content')
{{HTML::script('assets/js/password-strength.js')}}
<script type="text/javascript">
    $(document).ready(function () {
        $('#password').pwstrength();
    });
</script>
<div class="container-fluid pass-recovery-wrapper">
    <div class="conatiner">
        <div class="row">
            <div class="col-lg-offset-4 col-lg-4 main-div-recovery">
<!--                <div class="success-msg">
                    <p class="success-msg-p">Your password is successfully changed. <a href="">Login</a> to continue</p>
                </div>-->
                <h2>Choose A New Password</h2>
                <div class="middle-text">
                    Enter your password in the box below. For added security it 
                    must contain at least one number and capital letter.
                </div>
                {{ Form::open(array('url' => '/reset-password', 'id'=>'reset_password')) }}    
                <div class="row recovery-form">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pull-left password-recovery-div">
                        <div class="label-confirm"><label class="label-confirm"  for="new-password"></label></div>
                        <input type="password" class="form-control"name="password" id="password" placeholder="New Password" title="Password must contain at least 8 characters, including UPPER/lowercase and numbers" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,14}" onchange="this.setCustomValidity(this.validity.patternMismatch ? this.title : '');" required>                        
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pull-right password-recovery-div">
                        <div class="label-confirm"><label class="label-confirm" for="recovery-confirm-password"></label></div>
                        <input type="password" id="confirm_password" name="re-password" class="form-control" placeholder="Confirm Password" required>                        
                    </div>
                </div>
                <div class="clearfix"></div>
                <input type="hidden" name="user_id" value="{{$user_id}}"/>
                <input type="hidden" name="reset_code" value="{{$reset_code}}"/>
                <button type="submit" class="btn btn-default recovery-submit" onclick="validatePassword()">Submit</button>
                {{ Form::close() }} 
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var password = document.getElementById("password"), confirm_password = document.getElementById("confirm_password");
        function validatePassword() {
            if (password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Passwords Don't Match");
            } else {
                confirm_password.setCustomValidity('');
            }
        }
        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    });
</script>
@endsection