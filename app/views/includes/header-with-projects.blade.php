<!DOCTYPE html>
<html lang="en">    
    <head>        
        <meta charset="utf-8">        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">        
        <meta name="viewport" content="width=device-width, initial-scale=1">        
        <title>ravabe</title>  
        {{HTML::style('assets/css/bootstrap.min.css')}}
        {{HTML::style('assets/css/perfect-scrollbar.min.css')}}
        {{HTML::style('assets/css/font-awesome.min.css')}}
        {{HTML::style('assets/js/jquery-ui-1.11.3/jquery-ui.css')}}
        {{HTML::style('assets/css/style.css')}}
        
        {{HTML::script('assets/js/jquery-2.1.1.min.js')}}
        {{HTML::script('http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js')}}
    </head>    
    <div class="hero-unit">        
        <div class="header">            
            <div class="container">                
                <div class="row topbar">                    
                    <div class="col-lg-2 col-md-6 col-sm-4 col-xs-6"><img src="assets/images/logo-ravabe.png"></div>                    
                    <div class="col-lg-7 col-md-6 col-sm-8 col-xs-6 col-lg-push-3">                        
                        <nav class="navbar navbar-default">                            
                            <div class="container-fluid">                                
                                <!-- Brand and toggle get grouped for better mobile display -->                                
                                <div class="navbar-header">                                    
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#topnav">                                        
                                        <span class="sr-only">Toggle navigation</span>                                        
                                        <span class="icon-bar"></span>                                        
                                        <span class="icon-bar"></span>                                        
                                        <span class="icon-bar"></span>                                    
                                    </button>                                
                                </div>                                
                                <!-- Collect the nav links, forms, and other content for toggling -->                                
                                <div class="collapse navbar-collapse" id="topnav">   
                                    <ul class="nav navbar-nav nav-middle">
                                        <li id="main-nav-item"><a href="#">Dashboard</a></li>                                        
                                        <li id="main-nav-item"><a href="#">Publishing</a></li>                                        
                                        <li id="main-nav-item"><a href="#">Settings</a></li>                                        
                                    </ul>
                                    <ul class="nav navbar-nav navbar-right">                                        
                                        <li id="dropdown">                                            
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><img src="assets/images/60.png" /> <span id="caret"></span></a>                                            
                                            <div class="top-arrow"></div>                                            
                                            <ul class="dropdown-menu" role="menu">                                                
                                                <li><a href="#"><i class="fa fa-male"></i>Account</a></li>                                                
                                                <li><a href="#"><i class="fa fa-user"></i>Profile</a></li>                                                
                                                <li><a href="#"><i class="fa fa-server"></i>Channels</a></li>                                                
                                                <li><a href="URL::('logout')"><i class="fa fa-sign-out"></i>Logout</a></li>                                            
                                            </ul>                                        
                                        </li>                                    
                                    </ul>                                
                                </div><!-- /.navbar-collapse -->                            
                            </div><!-- /.container-fluid -->                        
                        </nav>                    
                    </div>   
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 col-lg-pull-7 col-md-pull-0" id="project-list-widget">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                                PROJECT
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                <li>  
                                    <span>ALL PROJECTS</span>
                                    <button class="btn btn-orange" type="submit"><i class="fa fa-folder-open"></i> Add project</button>
                                    <div class="clearfix"></div>
                                </li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Project A</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Project B</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Project C</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Project D</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Project E</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Project F</a></li>
                            </ul>
                        </div>
                    </div>
                </div>            
            </div>        
        </div>    
    </div>