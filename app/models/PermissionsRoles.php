<?php 
 
 class PermissionsRoles extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permission_roles';
   
    protected $fillable = array('id', 'role', 'created_by', 'created_at', 'updated_at');
 }   