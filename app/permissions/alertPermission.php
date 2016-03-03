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
	    $Roles 	  =  DB::table('alert_subs_titles')->select('*')										
										->where('user_id', $userId)
										->get();
										
		$rolemodule = array();								
		if (sizeof($Roles)>0) {
			foreach($Roles as $r_k=> $Role){
				$rolemodule[] = array(
					'roleid' => $Role->permission_role,
					'alert_subs_titles_id' => $Role->id,
				);
			}
		}
		return $rolemodule;
	}//Correct
   
	public static function checkParentModule($id){
		$alltitles = DB::table('alert_types')
								->select('id')										
								->where('module_sub_id','=',$id)
								->get();
		if(count($alltitles) > 0)
			return true;
		else
			return false;
	}
	
	public static function getAlertModules($rolemodules) { 
		$getalertmodule = array();
		if(count($rolemodules)>0){
				$alert_subs_titles_id = $rolemodules['alert_subs_titles_id'];
				$alltitles = DB::table('alert_subs')
																->select('*')										
																->where('subs_title_id', $alert_subs_titles_id)
																->where('subscription_status', 1)
																->get();
								
				if (sizeof($alltitles)>0) {
					$titlesmodule = array();								
					foreach ($alltitles as $key => $vlaue) {
						if(alertPermission::checkParentModule($vlaue->role_module_id)){
							$getalertmodule[$alert_subs_titles_id][$rolemodules['roleid']][$vlaue->main_module_id][$vlaue->role_module_id] = alertPermission::getAlertTypesModes($vlaue->main_module_id,$vlaue->role_module_id);
						}
					}
				} 
		}
		return $getalertmodule;	
	}
	
	public static function getAlertTypesModes($moduleid ,$submodule) {
		
		$moduletype = DB::table('alert_types')
							->select('*')										
							->where('module_id', $moduleid)
							->where('module_sub_id', $submodule)
							->get();
							
		$modules = array();
		$Alertmodules = array();
	      foreach ($moduletype as $key => $role) {
			 $Alertmodules[$role->id] = array(
	           'id' => $role->id,
			   'type' => $role->type,
	           'color_code' => $role->color_code,
			   'module_id' => $role->module_id,
			   'module_sub_id' => $role->module_sub_id,
			   'message' => $role->message,
			   
			   );
			}
			//echo"<pre>";var_dump($Alertmodules);exit;	
          return $Alertmodules;
	}	
	
   	
}


?>
