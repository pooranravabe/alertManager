<?php 
 
 class RoleModules extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'role_modules';
   
    protected $fillable = array('id', 'module', 'p_id', 'cid', 'updated_at', 'created_at');
	
	public static function getModule(){
		$Modules=RoleModules::select('id','module')->get();
		return $Modules;
	}
	
	public static function getModulesBycategory($cat_id){
		$categories = RoleModules::where('cid', $cat_id)->get();
		return $categories;
	}
 }   