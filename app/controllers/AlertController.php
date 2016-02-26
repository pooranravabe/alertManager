<?php

class AlertController extends BaseController {

    public function __construct() {
        $this->beforeFilter('auth');
        parent::__construct();
        if (Sentry::check()) {
            $this->user = Sentry::getUser();
        }
    }

	public function manageAlertType(){
		$arrList = array();
		$arrAlertTypes = AlertTypes::getMainAlertTypes();	
		if(count($arrAlertTypes) > 0){
			foreach($arrAlertTypes AS $key=>$val){
				$arrList[$key]['id'] = $val['id'];
				$arrList[$key]['type'] = $val['type'];
				$arrList[$key]['subtype'] = 	$arrAlertTypes = AlertTypes::getAlertSubTypes($val['id']);						
			} 
		}
		return View::make('alert.alert-types')->with('data', $arrList);
	}
	
	
	public function addedMainAlert(){
		$alertName = addslashes(Input::get('alertName'));
		if($alertName != null){
			$checkAlert = AlertTypes::where('type', $alertName)->first();	
			if($checkAlert == null){
					$alerttype = new AlertTypes;
					$alerttype->type = $alertName;
					$alerttype->created_at = date('Y-m-d');
					$alerttype->updated_at = date('Y-m-d');
					$alerttype->save();
				Toastr::success('Organization Succesfully Added !!');	
			} 
			else{
				Toastr::error('Alerts already exist !!');
				return Redirect::to('alert/addType');
			}
		}
		else{
			Toastr::error('Alerts can not be blank !!');
			return Redirect::to('alert/addType');
			
		}		
  return Redirect::to('alert/allTypes');
		
	}
	
	public function addedSubAlert(){
		$alertSubName = addslashes(Input::get('alertSubName'));
		$alertTypeId = addslashes(Input::get('alertTypeId'));
		$mainAlertName = addslashes(Input::get('mainAlertName'));
		$alertSubMessage = addslashes(Input::get('alertSubMessage'));
		$alertColorCode = addslashes(Input::get('alertColorCode'));
		$moduleName = addslashes(Input::get('moduleName'));
		$subModuleId = addslashes(Input::get('subModuleId'));
		
		$varFromStatus = addslashes(Input::get('fromStatus'));
		$varFromPercent = addslashes(Input::get('fromPercent'));
		$varToStatus = addslashes(Input::get('toStatus'));
		$vartoPercent = addslashes(Input::get('toPercent'));
	
		if($alertSubName != null){
			$checkAlert = AlertTypes::where('type', $alertSubName)->where('parent_id', $alertTypeId)->first();	
			if($checkAlert == null){
					$alerttype = new AlertTypes;
					$alerttype->type = $alertSubName;
					$alerttype->color_code = $alertColorCode;
					$alerttype->message = $alertSubMessage;
					$alerttype->parent_id = $alertTypeId;
					$alerttype->module_id = $moduleName;
					$alerttype->module_sub_id = $subModuleId;
					
					$alerttype->range_from_status = $varFromStatus;
					$alerttype->range_from = $varFromPercent;
						$alerttype->range_to_status = $varToStatus;
					$alerttype->range_to = $vartoPercent;
					
					$alerttype->created_at = date('Y-m-d');
					$alerttype->updated_at = date('Y-m-d');
					$alerttype->save();
				Toastr::success('Alert Sub Type Succesfully Added!!');	
			} 
			else{ 
				Toastr::error('Alerts Sub type for '.$mainAlertName.' alert already exist !!');
				return Redirect::to('alert/allTypes');
			}
		}
		else{
			Toastr::error('Alerts Sub can not be blank !!');
			return Redirect::to('alert/allTypes');
			
		}		
  return Redirect::to('alert/allTypes');
		
	}
	
		public function UpdateMainAlert(){
		  $varUpdateName = Input::get('alertTypeEditName');
				if($varUpdateName != null){
						$varAlertTypeEditId = Input::get('alertTypeEditId');
						$checkAlert = AlertTypes::where('type', $varUpdateName)->where('id', '!=', $varAlertTypeEditId)->where('parent_id', '=', '0')->first();
						if($checkAlert == null){
							 $update = AlertTypes::where('id', $varAlertTypeEditId)->update(array('type' => $varUpdateName));
								Toastr::success('Alert type updated successfully !!');	
								return Redirect::to('alert/allTypes');
						}
						else {
							Toastr::error('Alert Type Name already exist !!');
							return Redirect::to('alert/allTypes');
						}
				}
				else {
						Toastr::error('Alerts can not be blank !!');
						return Redirect::to('alert/allTypes');
				}
	} // End UpdateOrganization function
		
	public function UpdateSubAlert(){ 
		  $varUpdateName = Input::get('alertTypeEditName');
				$alertSubParentId = Input::get('alertSubParentId');
				$alertColorCode = Input::get('alertColorCode');
				$alertSubMessage = Input::get('alertSubMessage');
				$moduleName = Input::get('moduleName');
				$subModuleId = Input::get('subModuleId');
				$fromStatus = Input::get('fromStatus');
				$fromPercent = Input::get('fromPercent');
				$toStatus = Input::get('toStatus');
				$toPercent = Input::get('toPercent');				
				if($varUpdateName != null){
						$varAlertTypeEditId = Input::get('alertTypeEditId');
						
						$checkAlert = AlertTypes::where('type', $varUpdateName)->where('id', '!=', $varAlertTypeEditId)->where('parent_id', '=', $alertSubParentId)->first();
						if($checkAlert == null){
							 $update = AlertTypes::where('id', $varAlertTypeEditId)->update(array('type' => $varUpdateName,'color_code' =>$alertColorCode,'message' =>$alertSubMessage, 'module_id' => $moduleName, 'module_sub_id' =>$subModuleId,'range_from_status'=>$fromStatus,'range_from'=>$fromPercent,'range_to_status'=>$toStatus,'range_to'=>$toPercent));
								Toastr::success('Alert Sub type updated successfully !!');	
								return Redirect::to('alert/allTypes');
						}
						else {
							Toastr::error('Alert Sub Type already exist for this alert type !!');
							return Redirect::to('alert/allTypes');
						}
				}
				else {
						Toastr::error('Alerts can not be blank !!');
						return Redirect::to('alert/allTypes');
				}
	}	
		
	public function editMainAlert(){
		 $alertName = Input::get('alertName');
		 $alertId = Input::get('alertId');
			return View::make('alert.edit-main')->with('alertTypeName', $alertName)->with('alerttypeId', $alertId);
	}
	
		public function editMainSubAlert(){ //echo "<pre>";print_r($_POST);die;
		 $alertName = Input::get('alertName');
		 $alertId = Input::get('alertId');
			$module_id = Input::get('module_id');
			$alertParentId = Input::get('alertParentId');
			$module_sub_id = Input::get('module_sub_id');
			$alert_message = Input::get('alert_message');
			$color_code = Input::get('color_code');
			
		 $from_status = Input::get('from_status');
			$range_from = Input::get('range_from');
			$to_status = Input::get('to_status');
			$range_to = Input::get('range_to');		
			
			
			$categories = DB::table('role_categories')->select('id','category')->get();
			//echo "<pre>";print_r($categories);die;
			$modobj = DB::table('role_modules')->select('id','module')->where("cid", "=",$module_id)->orderBy('id', 'ASC')->get();			
			$data = array('alertId'=>$alertId,'alertParentId'=>$alertParentId,'alertName'=>$alertName,'module_id'=>$module_id,'module_sub_id'=>$module_sub_id,'alert_message'=>$alert_message,'color_code'=>$color_code,'modulecategories'=>$categories,'sodulesubcategories'=>$modobj,'from_status'=>$from_status,'range_from'=>$range_from,'to_status'=>$to_status,'range_to'=>$range_to);	
			return View::make('alert.edit-sub')->with('data', $data);
	}
	
	public function addMainAlert(){
		return View::make('alert.add-main');
	}	
	
	public function addSubAlert(){		
		$alertId = Input::get('alertId');
		$alertName = Input::get('alertName');
		$categories = DB::table('role_categories')->select('id','category')->get();
		$data = array('alertId'=>$alertId,'alertName'=>$alertName,'modulecategories'=>$categories);	
		return View::make('alert.add-sub-main')->with('data', $data);
	}

	
	public function getSubModule(){
		$varModuleId = Input::get('moduleId');
		$varDropDown = '';
		if($varModuleId!='' && $varModuleId > 0){
			$modobj = DB::table('role_modules')->select('id','module')->where("cid", "=",$varModuleId)->orderBy('id', 'ASC')->get();
			
				$varDropDown = '<br>Sub Module Name:<br>';
				$varDropDown .= '<select class="list-select-box submodulename" name="subModuleId" id="sub_module" style="width:200px;" required>';
				$varDropDown .= '<option value="">Select Sub Moduel</option>';
					foreach($modobj as $key=>$val){
						$varDropDown .='<option value="'.$val->id.'">'.$val->module.'</option>';
					}
				$varDropDown .= '</select>';
		
		echo $varDropDown;
		}
		else{
				echo $varDropDown = '<br>Please select Module name';	
		}
		exit;
	}
	
	
	public function deleteMainAlert(){
			$varAlertTypeEditId = Input::get('alertId');
			if($varAlertTypeEditId > 0){
				$checkSubTypeAlert = AlertTypes::where('parent_id', $varAlertTypeEditId)->get();
				$varRecordsCount = count($checkSubTypeAlert);
				if($varRecordsCount == 0){
						$delete = AlertTypes::where('id', $varAlertTypeEditId)->delete();
						Toastr::success('Alerts type deleted successfully !!');	
						return Redirect::to('alert/allTypes');
				}
				else {
						Toastr::error('You can not delete it. Please delete there sub type first. !!');	
						return Redirect::to('alert/allTypes');
				}
			}
			
	}

		public function deleteSubAlert(){
			$varAlertTypeEditId = Input::get('alertSubId');
			if($varAlertTypeEditId > 0){
						$delete = AlertTypes::where('id', $varAlertTypeEditId)->delete();
						Toastr::success('Alerts sub type deleted successfully !!');	
						return Redirect::to('alert/allTypes');
				}
				else {
						Toastr::error('Please delete correctly. !!');	
						return Redirect::to('alert/allTypes');
				}
		}
			
	
	
	public function getAlertPage(){
		$page = (Input::has('page')) ? Input::get('page') : 1;
		$alert_data = array();
		$user_ids = array();
		$assigend_user_info = array();
		$fiters = array();
		if(Input::has('notification_filter')){
		  $fiters = Input::get('notification_filter');	
		}
		$filters = array(
			'user_id' => $this->user->id,
			'type' => $fiters,
			'sort_by' => Input::get('sort_by')
			);
		$alert_details = Alerts::getByAlertDetailsPerPage($filters);
		$timezone = Session::get('user_timezone');
		if (!$timezone) {
             $timezone = 'UTC';
           } 
           $message = '';
		foreach ($alert_details as $alert_detail) {
			  $alert_message = AlertStatus::where('alert_id', $alert_detail->id)->first();
			  if($alert_message){
			  	$message = $alert_message->message;
			  }
			  $alert_date_utc = new DateTime($alert_detail->updated_at, new DateTimeZone('UTC') );
	          $alert_date_utc->setTimezone(new DateTimeZone($timezone));
	          $alert_date_usertz = $alert_date_utc->format('Y-m-d H:i:s');
	          $alert_date_full = date('M j \a\t h:i A', strtotime($alert_date_usertz));
			$alert_data[] = array( 
               'message' => $message,
               'date' => $alert_date_full,
               'type' => $alert_detail->type,
               'user_info' =>  $assigend_user_info
			);
			
		}
		return View::make('alert.alert')->with(array('alert_details' => $alert_details, 'alert_details' => $alert_data, 'filters' => $fiters,'sort_by' => Input::get('sort_by')));
	}

	public function getAlertIntermediator($alert_id){
		$project_detals = Alerts::where('id', $alert_id)->first();
      	$alert = Alerts::where('id', $alert_id)->delete();
      	$alert_status = AlertStatus::where('alert_id', $alert_id)->delete();
		UserProjectHistory::setRecentProjects($this->user->id, $project_detals['entity_id']);
		return Redirect::to('/content-list/' . $project_detals['entity_id']);
	}
	public function getMarkAlertsAsRead(){
        $user_id = $this->user->id;
        $alert_details = Alerts::where('user_id',$user_id)->where('status', 0)->get();
        foreach ($alert_details as $alert_detail) {
            $alert_detail->status = 1;
            $alert_detail->save();
            $alert_status = AlertStatus::where('alert_id',$alert_detail->id)->where('status', 0)->first();
            $alert_status->status = 1;
            $alert_status->save();
        }
        return Response::json(array('status' => 'success'));
    }
}