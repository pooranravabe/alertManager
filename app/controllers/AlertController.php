<?php
class AlertController extends BaseController {

public $alertfixedpermission;

    public function __construct() {
        $this->beforeFilter('auth');
        parent::__construct();
        if (Sentry::check()) {
            $this->user = Sentry::getUser();
        }
		$this->alertfixedpermission['roleid']=38;
		$this->alertfixedpermission['mainModuleId']=1;
		$this->alertfixedpermission['SubTitleId']=1;
		$this->alertfixedpermission['SubModuleId']=53;
		
    }
	


	public function getAlertPage(){
	
	    $values = alertPermission::getRoleID(1);
		
		foreach($values as $ky =>$arr) {
		$alertvalues[$arr['roleid']]=alertPermission::getAlertModules($arr);
		}
		//echo"<pre>";
		//print_r($alertvalues);
		//die;
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
			  
			  
		$this->alertfixedpermission['roleid']=38;
		$this->alertfixedpermission['mainModuleId']=1;
		$this->alertfixedpermission['SubTitleId']=1;
		$this->alertfixedpermission['SubModuleId']=53;
		
			if(array_key_exists($this->alertfixedpermission['SubModuleId'],$alertvalues[$this->alertfixedpermission['roleid']][$this->alertfixedpermission['mainModuleId']][$this->alertfixedpermission['SubTitleId']])){
			echo "permission";
			 } else {
			 echo "no alert premission";
			 }
			
			
			 die;
			
				$alert_data[] = array( 
				   'message' => $message,
				   'date' => $alert_date_full,
				   'type' => $alert_detail->type,
				   'user_info' =>  $assigend_user_info,
				   'roleid'=>$this->alertfixedpermission['roleid'],
				   'SubModuleId'=>$this->alertfixedpermission['SubModuleId'],
				   'mainModuleId'=>$this->alertfixedpermission['mainModuleId'],
				   'SubTitleId'=>$this->alertfixedpermission['SubTitleId'],
				   'alertvalues'=>$alertvalues,
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
	
		public function publishalert(){
		$values = alertPermission::getRoleID(1);
		//echo"<pre>";
		//print_r($values);
		//die;
		foreach($values as $ky =>$arr) {
		$alertvalues=alertPermission::getAlertModules($arr);
		}
		//echo"<pre>";
		//print_r($alertvalues);
		echo json_encode($alertvalues);
		die;
		//echo"<pre>";var_dump($values1);exit;
	}
}