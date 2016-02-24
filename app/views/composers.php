<?php

View::composer('*', function($view) {
    $projects = array();
    $platforms = array();
    $user = array();
    $allowed_methods = array('general-settings', 'channel-settings', 'add-project','connect-channel');
    $with_data = array();
    if (Sentry::check()) {
        $user = Sentry::getUser();
        $with_data['user'] = $user;
        
        if(Session::has('success_login')){
            $with_data['show_modal'] = 'hide';
        }else{
            $with_data['show_modal'] = 'show';
            foreach ($allowed_methods as $method) {
                    if (Request::is("$method")) {
                        $with_data['show_modal'] = 'hide';
                    }
                } 
        }
      
            $view->with($with_data);
    }else {
        
    }
});

