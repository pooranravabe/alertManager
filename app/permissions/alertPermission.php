<?php
/* class to get permision list from database of binding of set of functions */ 
class alertPermission  {
	
	public $user = null;
	public $UserEmail = null;
	public $UserId = null;
	
	function __construct() {
		//$ath = new AuthController();		
    	
		
		if (Sentry::check()) {
			$this->user = Sentry::getUser();
		}
		
		$this->UserId = $this->user->id;
		$this->UserEmail =$this->user->email;
		
   }
	/* 
	Auther : Pradeep
	Auther Email : singh.pooran@gmail.com
	Created date : 2016/02/25
	Last updated : 2016/02/25
	Input :  Nil 
	Output : array of permission for  signed in user  
	Description:  array of permission for module  of signed in user   
	*/ 	
	
	
	 public static function tmprole() {
	 //return 'sumit';
	 $alert_subs_titles = alertPermission::getRoleID($userId=1);
	 
	//return $alert_subs_titles['alert_subs_titles_id'];
	 $modules = array();
	 if (sizeof($alert_subs_titles)>0) {
		$modules =  alertPermission::getAlertModules(1,$alert_subs_titles['roleid'],$alert_subs_titles['alert_subs_titles_id']);
	 }
	 return $modules;
	 } 
	 
	 public static function getRoleID($userId) {
	 
	    $Role 	  =  AlertSubsTitles::select('*')										
										->where('user_id', $userId)
										->first();
										
		$rolemodule = array();	
									
		if (sizeof($Role)>0) {
			$rolemodule['roleid']  = $Role->permission_role;
			$rolemodule['alert_subs_titles_id']  = $Role->id;
			}
	  //return 100;
		return $rolemodule;
	}
   
	
	public static function getAlertModules($userId,$roleid,$alert_subs_titles_id) {
	
	$alltitles 	  = DB::table('alert_subs')
										->select('*')										
										->where('user_id', $userId)
										->where('subs_title_id', $alert_subs_titles_id)
										->where('subscription_status', 1)
										->get();
										
	///echo "<pre>";
	//print_r($alltitles);
	//die;			
	
		
									
	if (sizeof($alltitles)>0) {
		$titlesmodule = array();								
	    foreach ($alltitles as $key => $vlaue) {
         $titlesmodule['main_module_id']  = $vlaue->main_module_id;
	      $titlesmodule['role_module_id']  = $vlaue->role_module_id;
	     
			  // $modules[$key]['subscription_status'] = $role->subscription_status;
$getalertmodule= alertPermission::getAlertTypesModes($titlesmodule['main_module_id'] ,$titlesmodule['role_module_id']);
			}
			return $getalertmodule;	
	     }
//echo $titlesmodule;
	
	}
	
	
	public static function getAlertTypesModes($moduleid ,$submodule) {
		$moduletype 	  = DB::table('alert_types')
										->select('*')										
										->where('module_id', $moduleid)
										->where('module_sub_id', $submodule)
										->get();
				
				//echo'<pre>';
				//print_r($moduletype);
				//var_dump($moduletype);
				//die;
		$modules = array();
	      foreach ($moduletype as $key => $role) {
	           $modules[$role->module_id][$role->module_sub_id]['id'] = $role->id;
	           $modules[$role->module_id][$role->module_sub_id]['color_code'] = $role->color_code;
			   $modules[$role->module_id][$role->module_sub_id]['module_id'] = $role->module_id;
			   $modules[$role->module_id][$role->module_sub_id]['module_sub_id'] = $role->module_sub_id;
			}
          return $modules;
				
	

	
	}	
}


?>