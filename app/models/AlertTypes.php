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
					$alert_sub_type = AlertTypes::where('parent_id', $id)->select('id', 'type','module_id','module_sub_id','message','color_code','range_from_status','range_to_status','range_from','range_to','subscription_id','subscription_role')->get()->toArray();
					$arr = array();
					if($alert_sub_type > 0){
						foreach($alert_sub_type as $key=>$val){
							$arr[$key]['id'] = $val['id'];
							$arr[$key]['type'] = $val['type'];
							$arr[$key]['module_id'] = $val['module_id'];
							$arr[$key]['sub_module_id'] = $val['module_sub_id'];
							$arr[$key]['message'] = $val['message'];
							$arr[$key]['color_code'] = $val['color_code'];
							$arr[$key]['range_from_status'] = $val['range_from_status'];
							$arr[$key]['range_from'] = $val['range_from'];
							$arr[$key]['range_to_status'] = $val['range_to_status'];
							$arr[$key]['range_to'] = $val['range_to'];
							$arr[$key]['subscription_id'] = $val['subscription_id'];
							$arr[$key]['subscription_role'] = $val['subscription_role'];
							if($val['module_id'] > 0){
								$modules = DB::table('role_categories')->select('category')->where("id", "=",$val['module_id'])->first();
								$arr[$key]['module_name'] = 	$modules->category;
								$submodules = DB::table('role_modules')->select('module')->where("id", "=",$val['module_sub_id'])->first();
								$arr[$key]['sub_module_name'] = $submodules->module;
							}
							else{
								$arr[$key]['module_name'] = 0;
							$arr[$key]['sub_module_name'] = 0;
							}
						}
						return 	$arr;
					}
					else
						return 	$arr;
				}
				
				public static function getCountAlertSubTypes($id){
				return $alert_sub_type = AlertTypes::where('parent_id', $id)->get()->toArray();
				 
				}

}