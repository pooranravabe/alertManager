@extends('layouts.protected')
@section('content')
{{HTML::script('assets/js/password-strength.js')}}


<div class="container-fluid general-setting-wrapper">
    <h3 class="text-center">MY ACCOUNT</h3>
    @include('widgets.settings-header') 
    <div class="row general-main">   
        <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2  col-xs-10 col-xs-offset-1"> 
            <div class="floating-form-general-main">  
                {{ Form::open(array('url' => 'update-setting','files'=> true,'id' => 'general-setting-form')) }}
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">                            
                        <label for="firstname">First Name</label>
                        <input type="text" class="form-control" id="firstname" placeholder="" pattern="^[a-z \A-Z \u4E00-\u9FA5\uF900-\uFA2D]{2,20}$" value="{{ $user->profile->firstname}}" oninvalid="this.setCustomValidity('First Name should be alphabatical and must be at least 2 characters long')" oninput="setCustomValidity('')" name="firstname">
                    </div>                       
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">                            
                        <label for="lastname">Last Name</label>                            
                        <input type="text" class="form-control" id="secondname" placeholder="" pattern="^[a-z \A-Z \u4E00-\u9FA5\uF900-\uFA2D]{1,20}$" value="{{ $user->profile->lastname}}" oninvalid="this.setCustomValidity('Last Name should be alphabatical and must be at least 1 characters long')" oninput="setCustomValidity('')" name="lastname">
                    </div> 
                </div> 
                <div class="form-group">                            
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" placeholder="" value="{{ $user->email}}" name="email" readonly="readonly" oninvalid="this.setCustomValidity('Please type a valid email address')" oninput="setCustomValidity('')" required>
                </div> 
                <div class="row">
                    <div style="position:relative;" class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group text-input">                            
                        <label for="password">New Password</label>
                        <input type="password" class="form-control" id="password" placeholder="" name="password" title="Password must contain at least 8 characters, including UPPER/lowercase and numbers" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,30}" onchange="this.setCustomValidity(this.validity.patternMismatch ? this.title : '');">
                    <p class="show eye"><i class="fa fa-eye"></i></p>                      
                    </div>  
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group text-password">                            
                        <ul class="password-rule">
                                <li>Password must contain 8 characters</li>
                                <li>Password must include uppercase characters</li>
                                <li>Password must include numbers</li>
                            </ul>
                    </div>   
                </div>
                <div class="general-seprator"></div>
                <div class="row">
                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-6 pull-left port-file">
                        <label for="profilephoto" class="col-sm-6 control-label">Profile Photo</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pull-left drop-file">
                        <input id="fileupload" style="position:absolute; left:-9999px;" type="file" name="profile_pic" />
                       
                       
                        
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"> 
                        <label class="col-lg-12 col-sm-12 col-md-12" for="timezone">Time Zone</label>   
                        <select name='user_tz' id="time-zone">
                            <option value="0" disabled {{($user->profile->timezone == '') ? 'selected="selected"' : ''}}>Choose your timezone</option>
                            @foreach ($timezone_details as $key => $value)
                            <option value="{{$key}}" {{($user->profile->timezone == $key) ? 'selected="selected"' : ''}}>{{$value}}</option>
                            @endforeach
                        </select>
                    </div> 
                </div>
                <div class="row timezone">                  
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">                            
                        <label for="jobtitle">Job Title</label>
                        <input type="text" class="form-control" id="jobtitle" name="job_title" placeholder="" value ="{{$user->profile->job_title}}">
                    </div>
                     <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">                            
                    <label for="organisation">Organisation</label>    
                    <input type="text" class="form-control" id="organisation" name="organisation" placeholder="" value ="{{$user->profile->organisation}}">
                </div>  
                </div>  
                <div class="general-seprator"></div>
                
               
            </div>
        </div>
    </div>
</div>
 

@endsection 