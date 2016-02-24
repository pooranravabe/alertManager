<?php

//use Illuminate\Auth\UserTrait;
//use Illuminate\Auth\UserInterface;
//use Illuminate\Auth\Reminders\RemindableTrait;
//use Illuminate\Auth\Reminders\RemindableInterface;
//
//class User extends Eloquent implements UserInterface, RemindableInterface {
//
//	use UserTrait, RemindableTrait;
//
//	/**
//	 * The database table used by the model.
//	 *
//	 * @var string
//	 */
//	protected $table = 'pooranusers';
//
//	/**
//	 * The attributes excluded from the model's JSON form.
//	 *
//	 * @var array
//	 */
//	protected $hidden = array('password', 'remember_token');
//
//}
use Cartalyst\Sentry\Users\Eloquent\User as SentryUser;

class User extends SentryUser {

    // Override the SentryUser getPersistCode method.
    protected $table = 'pooranusers';
    protected $hidden = array('password', 'remember_token');
    public function getPersistCode() {
        if (!$this->persist_code) {
            $this->persist_code = $this->getRandomString();

            // Our code got hashed
            $persistCode = $this->persist_code;

            $this->save();

            return $persistCode;
        }
        return $this->persist_code;
    }
    
    public function profile() {
        return $this->hasOne('Profile','user_id');
        
    }

    public function projectUsers(){
        return $this->hasMany('ProjectUsers', 'email');
    }

    public function content(){
        return $this->hasMany('Content', 'author_id', 'id');
    }
}
