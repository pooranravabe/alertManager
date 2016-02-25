<?php

class AlertSubsTitles extends Eloquent {



/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'alert_subs_titles'; 
    protected $fillable = array('id', 'user_id', 'title', 'permission_role', 'created_at', 'updated_at');

    


    
} // End AlertSubsTitles class




?>