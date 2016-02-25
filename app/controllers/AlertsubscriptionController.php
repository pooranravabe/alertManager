<?php

class AlertsubscriptionController extends BaseController {
	
	public $userid;
	public $email;
	
    public function __construct() {
        $this->beforeFilter('auth');
        parent::__construct();
        if (Sentry::check()) {
            $this->user = Sentry::getUser();
			$this->userid = $this->user->id;		   
		   $tmp = DB::table('alert_admin')->select('email')->where('id','=', $this->user->id)->first();
		   $this->email = $tmp->email;
        }
    }
	
	//author:@krrish
	//UpdatedAt:24/02/2016
	public function addAlertSubs(){
		$roles = PermissionsRoles::get();
		$categories = DB::table('role_categories')->select('id','category')->get();
		//$permissions = DB::table('permissions_sets')->select('id','cid','rid','mid','pread','pwrite', 'pupdate', 'pdelete')->get();
		$modules = array();
		foreach($categories  as $k => $cat){
			$modobj = DB::table('role_modules')->select('id','module','cid','p_id')->where("cid", "=",$cat->id)->orderBy('cid', 'ASC')->get();
			if($modobj) {					
				$modules[$cat->id]['category'] = $cat->category;
				$modules[$cat->id]['cid'] = $cat->id;
				$modules[$cat->id]['module'] = $modobj;						
			}
		}
		//$submodData = DB::table('role_modules')->select('id','module','p_id','cid')->get();
		$data = array('roles'=>$roles, 'categories'  =>$categories, 'modules'  =>$modules);		
		return View::make('alertmanage.addAlertSubs')->with('data', $data);
	}
	
	//author:@krrish
	//UpdatedAt:24/02/2016
	public function postAlertSubs(){
		$a_title = Input::get('a_title');
		$roleId = Input::get('roleId');
		if (array_key_exists('permisson', $_POST)) {
			$permisson 	= $_POST['permisson'];
			$userId = $this->user->id; 
			$chckTitle = AlertSubsTitles::where('title', $a_title)->first();
			if($chckTitle == null){
				$AlertSubsTitles = new AlertSubsTitles;
				$AlertSubsTitles->user_id = $userId;
				$AlertSubsTitles->title = $a_title;
				$AlertSubsTitles->permission_role = $roleId;
				$AlertSubsTitles->created_at = date('Y-m-d H:i:s', time());
				$AlertSubsTitles->updated_at = date('Y-m-d H:i:s', time());
				$AlertSubsTitles->save();
				$current_title_id = $AlertSubsTitles->id;
				foreach($permisson as $kp =>$per) {
					$onoff = 0;
					if (array_key_exists('onoff', $per)) {
						if($per['onoff']=="on") 
							$onoff = 1;
					}
					$AlertSubs  = new AlertSubs;
					$AlertSubs->user_id = $userId;
					$AlertSubs->subs_title_id = $current_title_id;
					$AlertSubs->role_module_id 	= $kp;	
					$AlertSubs->subscription_status = $onoff;
					$AlertSubs->created_at = date('Y-m-d H:i:s', time());
					$AlertSubs->updated_at = date('Y-m-d H:i:s', time());
					$AlertSubs->save();
					
				}
				Toastr::success('Alert Subscription successfully created !!');
				return Redirect::to('alertManage/viewAlertSubs');
			}
			else
			{
				Toastr::error('This Title is already exists !!');
				return Redirect::to('alertManage/addAlertSubs');		
			}
		}
		else {
			Toastr::error('Please select at least one permission check !!');
			return Redirect::to('alertManage/addAlertSubs');		
		}
	}
	
	//author:@krrish
	//UpdatedAt:24/02/2016
	public function viewAlertSubs(){
		
		$AlertsList = AlertSubsTitles::get();
		$data = array('AlertsList'  =>$AlertsList);		
		return View::make('alertmanage.alertSubs-list')->with('data', $data);
		//var_dump($AlertsList);exit;
	}
	
	//author:@krrish
	//UpdatedAt:24/02/2016
	public function deleteAlertSubs(){
		$title_id = Input::get('titleId');
		if($title_id!=null){				
				$deleteAlertSubsTitles = AlertSubsTitles::where('id', $title_id)->delete();
				$deleteAlertSubs = AlertSubs::where('subs_title_id', $title_id)->delete();
				Toastr::success('Alert Subscription successfully deleted !!');
				return Redirect::to('alertManage/viewAlertSubs');
		}
		else{
			Toastr::error('There is a problem to delete this Alert !!');
			return Redirect::to('alertManage/viewAlertSubs');
		}
		
	}
	
	//author:@krrish
	//UpdatedAt:24/02/2016
	public function editAlertSubs(){
		$cboxinfo = array();
		$title_id = Input::get('titleId');		
		$title_name = Input::get('titleName');
		$title_role = Input::get('titleRole');
		$title_info = AlertSubs::select('id','role_module_id')->where('subs_title_id', $title_id)->get();
		foreach($title_info as $k_ti=> $title){
			$cboxinfo[] = $title['role_module_id'];
		}
		$roles = PermissionsRoles::get();
		$categories = DB::table('role_categories')->select('id','category')->get();
		$modules = array();
		foreach($categories  as $k => $cat){
			$modobj = DB::table('role_modules')->select('id','module','cid','p_id')->where("cid", "=",$cat->id)->orderBy('cid', 'ASC')->get();
			if($modobj) {					
				$modules[$cat->id]['category'] = $cat->category;
				$modules[$cat->id]['cid'] = $cat->id;
				$modules[$cat->id]['module'] = $modobj;						
			}
		}
		$data = array('roles'=>$roles, 'categories'  =>$categories, 'modules'  =>$modules, 'title_name'=>$title_name, 'title_id'=>$title_id, 'title_role'=>$title_role, 'title_info'=>$cboxinfo);	
		//echo"<pre>";var_dump(in_array(20,$cboxinfo));exit;
		return View::make('alertmanage.edit-AlertSubs')->with('data', $data);
		
		
	}
	
	//author:@krrish
	//UpdatedAt:24/02/2016
	public function updateAlertSubs(){
		$a_title_id = Input::get('a_title_id');
		$a_title = Input::get('a_title');
		$roleId = Input::get('roleId');
		if (array_key_exists('permisson', $_POST)) {
			if($a_title_id!=null && $a_title!=null) {
								
				$update = AlertSubsTitles::where('id', $a_title_id)->update(array('title' => $a_title, 'permission_role' => $roleId));					
				$permisson = $_POST['permisson'];
				$userId = $this->user->id; 			
				AlertSubs::where('subs_title_id', $a_title_id)->delete();	
				foreach($permisson as $kp =>$per) {
					$onoff = 0;
					if (array_key_exists('onoff', $per)) {
						if($per['onoff']=="on") 
							$onoff = 1;
					}
					$AlertSubs  = new AlertSubs;
					$AlertSubs->user_id = $userId;
					$AlertSubs->subs_title_id = $a_title_id;
					$AlertSubs->role_module_id 	= $kp;	
					$AlertSubs->subscription_status = $onoff;
					$AlertSubs->created_at = date('Y-m-d H:i:s', time());
					$AlertSubs->updated_at = date('Y-m-d H:i:s', time());
					$AlertSubs->save();
				}
			Toastr::success('Alert successfully updated !!');
			return Redirect::to('alertManage/viewAlertSubs');				
			}
		}
		Toastr::success('Please select at least one permission check to update any role. !!');
			return Redirect::to('alertManage/viewAlertSubs');
	}
	
	
	//Editor:@krrish
	//UpdatedAt:29/12/2015	
	public function GetModules() {
	   $Chklolcation =  new checkLocations();
		$Chklolcation->saveinfo();
		if($this->userid){
				$categories = DB::table('role_categories')->select('id','category')->get();
				$subData = DB::table('role_modules')->select('id','module','p_id','cid')->get();
				$data = array( 'message'  =>"",'categories'  =>$categories,'subData' =>$subData);
				return View::make('roles.view-roles')->with('data', $data);
			} else {
			 $cookie = array('email' => '');
		 	 return View::make('auth.login')->with(array('cookie' =>$cookie));	
		}
	}
	
	public function AddModules() {
      $Chklolcation =  new checkLocations();
		$Chklolcation->saveinfo();		
		
		$cat = Input::get('cat');		
	
		if($this->userid && $cat) {				
				$modules 		 = DB::table('role_modules')->select('id','module')->get();
				$data = array('modules'  =>$modules,'cat' =>$cat);					
				return View::make('roles.add-roles')->with('data', $data);
			} else {
			 $cookie = array('email' => '');
		 	 return View::make('auth.login')->with(array('cookie' =>$cookie));	
		}
	}
	
	//Author:@krrish
	//CreatedAt:30/12/2015
	public function EditSubModule() {		
		
		$subModuleId = Input::get('subModuleId');		
		$subModuleName = Input::get('subModuleName');
		if($subModuleId!=null && $subModuleName!=null) {				
				$update = DB::table('role_modules')->where('id', $subModuleId)->update(array('module' => $subModuleName));				
				return Redirect::to('roles/module');
			} else {
			 $cookie = array('email' => '');
		 	 return View::make('auth.login')->with(array('cookie' =>$cookie));	
		}
	}
	
	//Author:@krrish
	//CreatedAt:30/12/2015
	public function DeleteSubModule() {		
		
		$subModuleId = Input::get('subModuleId');		
		if($subModuleId!=null) {				
				$delete = DB::table('role_modules')->where('id', $subModuleId)->delete();				
				return Redirect::to('roles/module');
			} else {
			 $cookie = array('email' => '');
		 	 return View::make('auth.login')->with(array('cookie' =>$cookie));	
		}
	}
	
	public function SaveModules() {
	
		if($this->userid) {
			$parent = Input::get('parent');
			$module = Input::get('module');
			$cat 	= Input::get('cat');
			$checkModule = RoleModules::where('module', $module)->first();
				if($checkModule == null){
					$RoleModules = new RoleModules;
					$RoleModules->p_id = $parent;
					$RoleModules->module = $module;
					$RoleModules->cid = $cat ;			
					$RoleModules->save();
					return Redirect::to('roles/module');			
				}
				else{
					
					return Redirect::to('roles/module');
				}
			} else {
			 $cookie = array('email' => '');
		 	 return View::make('auth.login')->with(array('cookie' =>$cookie));	
		}
	}
	
	public function Permissions() {
		// url location
        $Chklolcation =  new checkLocations();
		$Chklolcation->saveinfo();
		
		if($this->userid ) {
				$categories 		 = DB::table('role_categories')->select('id','category')->get();
				$permissions 		 = DB::table('permissions_sets')->select('id','cid','rid','mid','pread','pwrite', 'pupdate', 'pdelete')->get();
			
				$modules = array();
				foreach($categories  as $k => $cat) {
					 $modobj 		 = DB::table('role_modules')->select('id','module','cid','p_id')->where("cid", "=",$cat->id)->orderBy('cid', 'ASC')->get();
				
					if($modobj) {					
					$modules[$cat->id]['category'] = $cat->category;
					$modules[$cat->id]['cid'] = $cat->id;
					$modules[$cat->id]['module'] = $modobj;						
					}
				}			
			
			  $data = array('categories'  =>$categories,'modules'  =>$modules);
             return View::make('roles.permissions')->with('data', $data);
			 	
			} else {
			 $cookie = array('email' => '');
		 	 return View::make('auth.login')->with(array('cookie' =>$cookie));	
		}
	}
	public function AddPermissions(){
		// url location
        $Chklolcation =  new checkLocations();
		$Chklolcation->saveinfo();
		// check is there any permission checked
		if (array_key_exists('permisson', $_POST)) {	
			$permisson 	= $_POST['permisson'];
			$userId 	   = $this->user->id; 
			$role_name 	= Input::get('role_name');
			$chckName 	 = PermissionsRoles::where('role', $role_name)->first();	
			// check is there any exist role by the same name				
			if($chckName == null){
				$permissionsRoles = new PermissionsRoles;
				$permissionsRoles->role = $role_name;
				$permissionsRoles->created_by = $userId;
				$permissionsRoles->created_at = date('Y-m-d H:i:s', time());
				$permissionsRoles->updated_at = date('Y-m-d H:i:s', time());
				$permissionsRoles->save();
				$current_role_id = $permissionsRoles->id;
			
			foreach($permisson as $kp =>$per) {
				$veiw = 0;
				$write = 0;
				$update = 0;
				$delete = 0;
				
				if (array_key_exists('pread', $per)) {
					if($per['pread']=="on") 
						$veiw = 1;
					
				}
			
				if (array_key_exists('pwrite', $per)) {
					if($per['pwrite']=="on") 
						$write = 1;
					
				}
				if (array_key_exists('pupdate', $per)) {
					if($per['pupdate']=="on") 
						$update = 1;
					
				}
				
				if (array_key_exists('pdelete', $per)) {
					if($per['pdelete']=="on") 
						$delete = 1;
					
				}			
				
				$PermissionsSet  = new PermissionsSets;
				$PermissionsSet->rid = $current_role_id;
				$PermissionsSet->cid = $_REQUEST['category'][$kp]['cid'] ;
				$PermissionsSet->mid = $kp;
				$PermissionsSet->pread 	= $veiw;	
				$PermissionsSet->pwrite = $write;
				$PermissionsSet->pupdate = $update;	
				$PermissionsSet->pdelete = $delete;
				$PermissionsSet->created_by = $userId;			
				$PermissionsSet->save();
				}
				Toastr::success('Role successfully created !!');
				return Redirect::to('roles/allRoles');	
			}
			else
			{
				Toastr::error('This role is already exists !!');
				return Redirect::to('roles/permissions');		
			}
			// end  check is there any exist role by the same name	
			
		} else {
			Toastr::error('Please select at least one permission check !!');
			return Redirect::to('roles/permissions');		
		}
		// end check is there any permission checked
		
	}
	
	//Auther:@krrish
	//CreatedAt:31/12/2015	
	public function GetRoles(){
		// url location
        $Chklolcation =  new checkLocations();
		$Chklolcation->saveinfo();
		//$Chklolcation =  new checkLocations();
		//$Chklolcation->saveinfo();
		$roles = PermissionsRoles::get();
		$data = array('roles'  =>$roles);		
		return View::make('roles.permissions-roles')->with('data', $data);
			
	}
	public function EditRole($pid){
		$roleId = $pid;
		$tmpRole = DB::table('permission_roles')->select('role')->where('id',$roleId)->first();
					
		$roleName = $tmpRole->role;		
		
		$categories = DB::table('role_categories')->select('id','category')->get();
		
		$modules = array();	
			foreach($categories  as $k => $cat) {
				 $modobj = DB::table('role_modules')->select('id','module','cid','p_id')->where("cid", "=",$cat->id)->orderBy('cid', 'ASC')->get();
				if($modobj) {					
					$modules[$cat->id]['category'] = $cat->category;
					$modules[$cat->id]['cid'] = $cat->id;
					$modules[$cat->id]['module'] = $modobj;						
				}
			}
		$SetsData =  PermissionsSets::where('rid', $roleId)->get();
		$data = array('categories'  =>$categories,'modules'  =>$modules, 'roleId'=>$roleId, 'roleName'=>$roleName, 'SetsData'=>$SetsData);
		return View::make('roles.edit-permissions')->with('data',$data);
	}
	
	public function UpdateRole() {
		$roleId = Input::get('role_id');
		if (array_key_exists('permisson', $_POST)) {		
			$roleId = Input::get('role_id');
			$roleName = Input::get('role_name');
			if($roleId!=null && $roleId!=null) {
								
				$update = PermissionsRoles::where('id', $roleId)->update(array('role' => $roleName));					
				$permisson = $_POST['permisson'];
				$userId = $this->user->id; 			
				PermissionsSets::where('rid', $roleId)->delete();	
			
			foreach($permisson as $kp =>$per) {
							
				$veiw = 0;
				$write = 0;
				$update = 0;
				$delete = 0;
				
				
				if (array_key_exists('pread', $per)) {
					if($per['pread']=="on") 
						$veiw = 1;
					
				}
			
				if (array_key_exists('pwrite', $per)) {
					if($per['pwrite']=="on")
						$write = 1;				
				}
				
				if (array_key_exists('pupdate', $per)) {
					if($per['pupdate']=="on")
						$update = 1;
				}
				
				if (array_key_exists('pdelete', $per)) {
					if($per['pdelete']=="on") 
						$delete = 1;
					
				}			
		
				$PermissionsSet  = new PermissionsSets;
				$PermissionsSet->rid = $roleId;
				$PermissionsSet->cid = $_REQUEST['category'][$kp]['cid'] ;
				$PermissionsSet->mid = $kp;
				$PermissionsSet->pread 	= $veiw;	
				$PermissionsSet->pwrite = $write;
				$PermissionsSet->pupdate = $update;	
				$PermissionsSet->pdelete = $delete;
				$PermissionsSet->created_by = $userId;			
				$PermissionsSet->save();
				}			
			}

			Toastr::success('Role successfully updated !!');
			return Redirect::to('roles/allRoles');
		} else {
			Toastr::error('Please select at least one permission check to update any role. !!');
			return Redirect::to('/roles/editRole/'.$roleId);	 		
					
		}
		// end check is there any permission checked
	}
	public function DeleteRole(){
		
		$roleId = Input::get('roleId');
		if($roleId!=null) {
			$delete = PermissionsSets::where('rid', $roleId)->delete();				
			$delete = PermissionsRoles::where('id', $roleId)->delete();							
		}
		Toastr::success('Role successfully deleted !!');
		return Redirect::to('roles/allRoles');
	}
	
	public function checkPerClass(){
		$checkpermission = new checkPermission();
		$ret=$checkpermission->getPublishPermisions(9);
		echo"<pre>";
		var_dump($ret);exit;
	}
	
}