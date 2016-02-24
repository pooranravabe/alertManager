<?php
/* class to get permision list from database of binding of set of functions */ 
class checkPermission {
	
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
	Auther : Pooran singh
	Auther Email : singh.pooran@gmail.com
	Created date : 2016/01/04
	Last updated : 2016/01/04
	Input :  Nil 
	Output : array of permission for module 'Publish' of signed in user  
	Description:  array of permission for module 'Publish' of signed in user   
	*/ 	
	function getPublishPermisions() {
		
	}
	/* 
	Auther : Pooran singh
	Auther Email : singh.pooran@gmail.com
	Created date : 2016/01/04
	Last updated : 2016/01/04
	Input :  Nil 
	Output : array of permission for module 'Publish' of signed in user  
	Description:  array of permission for module 'Response' of signed in user   
	*/ 	
	function getResponsePermisions() {
		
	}
	/* 
	Auther : Pooran singh
	Auther Email : singh.pooran@gmail.com
	Created date : 2016/01/04
	Last updated : 2016/01/04
	Input :  Nil 
	Output : array of permission for module 'Publish' of signed in user  
	Description:  array of permission for module 'Idea' of signed in user   
	*/ 	
	function getIdeaPermisions() {
		
	}
	/* 
	Auther : Pooran singh
	Auther Email : singh.pooran@gmail.com
	Created date : 2016/01/04
	Last updated : 2016/01/04
	Input :  Nil 
	Output : array of permission for module 'Publish' of signed in user  
	Description:  array of permission for module 'Organization' of signed in user   
	*/ 	
	function getOrganizationPermisions() {
		
	}
	/* 
	Auther : Pooran singh
	Auther Email : singh.pooran@gmail.com
	Created date : 2016/01/04
	Last updated : 2016/01/04
	Input :  Nil 
	Output : array of read , write , view and delete permisions for module 'Publish' of signed in user  
	Description:  is signed in user have permissions of particular submodule 
	*/ 	
	public function getSubModulePermisions($mainmodule,$submodule) {
		
		echo $this->UserId ;
		echo "sssssssssssss";
		echo $this->UserEmail;
		die;
		
	 $permissions = permissions_sets::where('email', $this->user->email)->
        where('project_id', $project_id)->first();	
		
		 $project_assigned = ProjectUsers::where('email', $this->user->email)->
        where('project_id', $project_id)->first();	
	}
}


?>
