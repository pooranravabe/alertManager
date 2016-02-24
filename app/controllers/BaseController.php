<?php

class BaseController extends Controller {

    public $user = null;
   
    
    public function __construct() {
        if (Sentry::check()) {
            $this->user = Sentry::getUser();
            $profile = Profile::where('user_id', $this->user->id)->first();
           // $user_profile_pic = ($profile->photo) ? URL::asset('uploads/' . $profile->photo) : URL::asset('assets/images/60.png');
           // View::share('user_profile_pic', $user_profile_pic);
        }
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout() {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

}
