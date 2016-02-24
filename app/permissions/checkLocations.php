<?php
/* class to get locations list from database of binding of set of functions */ 
class checkLocations {
	
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
	
public function  saveinfo() {
	
	 $uid= $this->UserId ;
	 $this->UserEmail;
	//print_r($_SERVER);
	//echo $req= $_SERVER['REMOTE_ADDR'].'-'.$_SERVER['REQUEST_URI'];
	 $req= $_SERVER['REQUEST_URI'];
	$saveloc= User::where('id', $uid)->update(array('last_known_location' => $req));
	
	//die;
	    
	}
	
public function addclick()  {

 // assume you have a clicks  field in your DB

 $this->clicks = $this->clicks + 1;
 $this->save();

}
	
	public function get($tokenKey) {
		
		return Redirect::to(Input::get('referer'));
        if(isset($tokenKey)) {
            return $this->$tokenKey;
        }
        return null;
    }
		
	
}


//checkLocations::getSubModulePermisions('Project');
?>
