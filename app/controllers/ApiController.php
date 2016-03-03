<?php

class ApiController extends BaseController {

    public function __construct() {
        $this->beforeFilter('auth');
        parent::__construct();
        if (Sentry::check()) {
            $this->user = Sentry::getUser();
        }
    }
			
				public function alertapi(){
					$alertvalues = array();
					$values = alertPermission::getRoleID(1);
					foreach($values as $ky =>$arr) {
						$alertvalues = alertPermission::getAlertModules($arr);
					}
					
					if(count($alertvalues)<=0)
						$alertvalues = array('error'=>'No Response found');	

					
					echo json_encode($alertvalues);
				}
	
}