<?php 

class AlertTypes extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'alert_types';
				
				// Function add for get alert types
				public static function getMainAlertTypes(){
					return $alert_type = AlertTypes::where('parent_id', 0)->select('id', 'type')->get()->toArray();
				}
				
				public static function getAlertSubTypes($id){ 
					return $alert_sub_type = AlertTypes::where('parent_id', $id)->select('id', 'type')->get()->toArray();
				}
				
				public static function getCountAlertSubTypes($id){
				return $alert_sub_type = AlertTypes::where('parent_id', $id)->get()->toArray();
				 
				}

}