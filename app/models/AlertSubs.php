<?php

class AlertSubs extends Eloquent {



/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'alert_subs'; 
    protected $fillable = array('id', 'user_id', 'subs_title_id', 'permissions_roles', 'role_module_id', 'subscription_status', 'created_at', 'updated_at');

    


    
} // End AlertSubs class




?>