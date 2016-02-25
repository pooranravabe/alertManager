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
		if($alertSubName != null){
			$checkAlert = AlertTypes::where('type', $alertSubName)->where('parent_id', $alertTypeId)->first();	
			if($checkAlert == null){
					$alerttype = new AlertTypes;
					$alerttype->type = $alertSubName;
					$alerttype->parent_id = $alertTypeId;
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
				if($varUpdateName != null){
						$varAlertTypeEditId = Input::get('alertTypeEditId');
						
						$checkAlert = AlertTypes::where('type', $varUpdateName)->where('id', '!=', $varAlertTypeEditId)->where('parent_id', '=', $alertSubParentId)->first();
						if($checkAlert == null){
							 $update = AlertTypes::where('id', $varAlertTypeEditId)->update(array('type' => $varUpdateName));
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
	
		public function editMainSubAlert(){
		 $alertName = Input::get('alertName');
		 $alertId = Input::get('alertId');
			$alertParentId = Input::get('alertParentId');
			return View::make('alert.edit-sub')->with('alertTypeName', $alertName)->with('alertTypeId', $alertId)->with('alertparentId', $alertParentId);
	}
	
	public function addMainAlert(){
		return View::make('alert.add-main');
	}	
	
	public function addSubAlert(){
		$alertId = Input::get('alertId');
		$alertName = Input::get('alertName');		
		return View::make('alert.add-sub-main')->with('alertId', $alertId)->with('alertName', $alertName);
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