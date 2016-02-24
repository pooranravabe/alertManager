<?php

class Profile extends Eloquent {

    protected $table = 'profiles';
    protected $primaryKey = 'profile_id';

    public function profile() {
        return $this->belongsTo('User', 'user_id');
    }

    public static function getUserEmailByUserId($user_id){
      $profile_details = '';
      $profile_details = User::where('id', $user_id)->first();
        if($profile_details){
          return $profile_details->email;
        }
        return $profile_details;
    }
    public static function getUserNameByUserId($user_id){
        $profile = Profile::where('user_id', $user_id)->first();
        if($profile){
            $name = $profile->firstname. $profile->lastname;
        }
        return $name;
    }
    public static function getUserDetailById($user_id, $approvel_status){
      $assigned_user_info = array();
      $profile_details = Profile::whereIN('user_id', $user_id)->get();
          if($approvel_status == '0'){
              $approvel_status = 'question';
          }elseif ($approvel_status == '1'){
             $approvel_status = 'check';
          }else{
             $approvel_status = 'close';
          }
          foreach ($profile_details as $profile_detail) {
            $assigned_user_info[] = array(
               'name' => $profile_detail->firstname .' '. $profile_detail->lastname,
               'approval_status' => $approvel_status,
               'image' => ($profile_detail->photo != '') ? URL::asset('uploads/'.$profile_detail->photo) : URL::asset('assets/images/60.png')
          ); 
          }
       
       return $assigned_user_info;
    }
    public static function getUserTimeZonelist(){
      $timezone_list = array( 
                    'Pacific/Midway' => '(UTC -11:00) Pacific/Midway',
                    'Pacific/Tahiti'=> '(UTC -10:00) Pacific/Tahiti',
                    'America/Anchorage' => '(UTC -08:00) America/Anchorage',
                    'America/Nome' => '(UTC -08:00) America/Nome',
                    'America/Sitka' => '(UTC -08:00) America/Sitka',
                    'America/Los_Angeles'  => '(UTC -07:00) America/Los_Angeles',
                    'America/Vancouver' => '(UTC -07:00) America/Vancouver',
                    'America/Denver' =>   '(UTC -06:00) America/Denver',
                    'America/Edmonton' => '(UTC -06:00) America/Edmonton',
                    'America/El_Salvador' => '(UTC -06:00) America/El_Salvador',
                    'Pacific/Easter'=> '(UTC -06:00) Pacific/Easter',
                    'America/Bogota'=> '(UTC -05:00) America/Bogota',
                    'America/Chicago'=> '(UTC -05:00) America/Chicago',
                    'America/Jamaica' =>'(UTC -05:00) America/Jamaica',
                    'America/Lima' => '(UTC -05:00) America/Lima',
                    'America/Mexico_City' => '(UTC -05:00) America/Mexico_City',
                    'America/Winnipeg' =>'(UTC -05:00) America/Winnipeg',
                    'America/Caracas' => '(UTC -04:30) America/Caracas',
                    'America/Barbados' => '(UTC -04:00) America/Barbados',
                    'America/Detroit' => '(UTC -04:00) America/Detroit',
                    'America/Indiana/Indianapolis'=> '(UTC -04:00) America/Indiana/Indianapolis',
                    'America/Kentucky/Louisville' => '(UTC -04:00) America/Kentucky/Louisville',
                    'America/New_York' => '(UTC -04:00) America/New_York',
                    'America/Santiago' => '(UTC -04:00) America/Santiago',
                    'America/Toronto' => '(UTC -04:00) America/Toronto',
                    'America/Argentina/Buenos_Aires' => '(UTC -03:00) America/Argentina/Buenos_Aires',
                    'America/Halifax' => '(UTC -03:00) America/Halifax',
                    'America/Sao_Paulo' => '(UTC -03:00) America/Sao_Paulo',
                    'Atlantic/Bermuda' => '(UTC -03:00) Atlantic/Bermuda',
                    'Atlantic/Cape_Verde' => '(UTC -01:00) Atlantic/Cape_Verde',
                    'Africa/Casablanca' => '(UTC -00:00) Africa/Casablanca',
                    'Africa/Dakar' => '(UTC -00:00) Africa/Dakar',
                    'UTC' => '(UTC -00:00) UTC',
                    'Africa/Algiers' => '(UTC +01:00) Africa/Algiers',
                    'Africa/Lagos' => '(UTC +01:00) Africa/Lagos',
                    'Africa/Porto-Novo' => '(UTC +01:00) Africa/Porto-Novo',
                    'Africa/Tunis' => '(UTC +01:00) Africa/Tunis',
                    'Atlantic/Canary'=> '(UTC +01:00) Atlantic/Canary',
                    'Europe/Dublin' => '(UTC +01:00) Europe/Dublin',
                    'Europe/London' => '(UTC +01:00) Europe/London',
                    'Africa/Cairo' => '(UTC +02:00) Africa/Cairo',
                    'Africa/Johannesburg' => '(UTC +02:00) Africa/Johannesburg',
                    'Africa/Tripoli' => '(UTC +02:00) Africa/Tripoli',
                    'Europe/Amsterdam' => '(UTC +02:00) Europe/Amsterdam',
                    'Europe/Berlin' => '(UTC +02:00) Europe/Berlin',
                    'Europe/Budapest' => '(UTC +02:00) Europe/Budapest',
                    'Europe/Copenhagen' => '(UTC +02:00) Europe/Copenhagen',
                    'Europe/Luxembourg' => '(UTC +02:00) Europe/Luxembourg',
                    'Europe/Madrid' => '(UTC +02:00) Europe/Madrid',
                    'Europe/Oslo' => '(UTC +02:00) Europe/Oslo',
                    'Europe/Paris' => '(UTC +02:00) Europe/Paris',
                    'Europe/Rome' => '(UTC +02:00) Europe/Rome',
                    'Europe/Stockholm' => '(UTC +02:00) Europe/Stockholm',
                    'Europe/Vienna' => '(UTC +02:00) Europe/Vienna',
                    'Europe/Zurich' => '(UTC +02:00) Europe/Zurich',
                    'Africa/Dar_es_Salaam' => '(UTC +03:00) Africa/Dar_es_Salaam',
                    'Africa/Kampala' => '(UTC +03:00) Africa/Kampala',
                    'Africa/Nairobi' => '(UTC +03:00) Africa/Nairobi',
                    'Asia/Baghdad' => '(UTC +03:00) Asia/Baghdad',
                    'Asia/Bahrain' => '(UTC +03:00) Asia/Bahrain',
                    'Asia/Damascus' => '(UTC +03:00) Asia/Damascus',
                    'Asia/Jerusalem' => '(UTC +03:00) Asia/Jerusalem',
                    'Asia/Kuwait' => '(UTC +03:00) Asia/Kuwait',
                    'Asia/Qatar'=> '(UTC +03:00) Asia/Qatar',
                    'Europe/Istanbul'=> '(UTC +03:00) Europe/Istanbul',
                    'Europe/Kiev'=> '(UTC +03:00) Europe/Kiev',
                    'Europe/Sofia'=> '(UTC +03:00) Europe/Sofia',
                    'Asia/Dubai'=> '(UTC +04:00) Asia/Dubai',
                    'Europe/Moscow'=> '(UTC +04:00) Europe/Moscow',
                    'Indian/Mauritius'=> '(UTC +04:00) Indian/Mauritius',
                    'Asia/Tehran'=> '(UTC +04:30) Asia/Tehran',
                    'Indian/Maldives'=> '(UTC +05:00) Indian/Maldives',
                    'Asia/Kolkata'=> '(UTC +05:30) Asia/Kolkata',
                    'Asia/Colombo'=> '(UTC +05:30) Asia/Colombo',
                    'Asia/Dhaka'=> '(UTC +06:00) Asia/Dhaka',
                    'Asia/Bangkok'=> '(UTC +07:00) Asia/Bangkok',
                    'Asia/Ho_Chi_Minh'=> '(UTC +07:00) Asia/Ho_Chi_Minh',
                    'Asia/Jakarta'=> '(UTC +07:00) Asia/Jakarta',
                    'Asia/Phnom_Penh'=> '(UTC +07:00) Asia/Phnom_Penh',
                    'Antarctica/Casey'=> '(UTC +08:00) Antarctica/Casey',
                    'Asia/Brunei'=> '(UTC +08:00) Asia/Brunei',
                    'Asia/Hong_Kong'=> '(UTC +08:00) Asia/Hong_Kong',
                    'Asia/Kuala_Lumpur'=> '(UTC +08:00) Asia/Kuala_Lumpur',
                    'Asia/Shanghai'=> '(UTC +08:00) Asia/Shanghai',
                    'Asia/Beijing' => '(UTC +08:00) Asia/Beijing',
                    'Asia/Singapore' =>'(UTC +08:00) Asia/Singapore',
                    'Australia/Perth' =>'(UTC +08:00) Australia/Perth',
                    'Asia/Pyongyang' =>'(UTC +09:00) Asia/Pyongyang',
                    'Asia/Seoul' =>'(UTC +09:00) Asia/Seoul',
                    'Asia/Tokyo' =>'(UTC +09:00) Asia/Tokyo',
                    'Australia/Adelaide' =>'(UTC +09:30) Australia/Adelaide',
                    'Australia/Darwin' =>'(UTC +09:30) Australia/Darwin',
                    'Asia/Yakutsk' =>'(UTC +10:00) Asia/Yakutsk',
                    'Australia/Brisbane' =>'(UTC +10:00) Australia/Brisbane',
                    'Australia/Melbourne' =>'(UTC +10:00) Australia/Melbourne',
                    'Australia/Sydney' =>'(UTC +10:00) Australia/Sydney',
                    'Asia/Vladivostok' =>'(UTC +11:00) Asia/Vladivostok',
                    'Asia/Kamchatka' =>'(UTC +12:00) Asia/Kamchatka',
                    'Pacific/Auckland' =>'(UTC +12:00) Pacific/Auckland',
                    'Pacific/Fiji' =>'(UTC +12:00) Pacific/Fiji',
                    'Pacific/Apia' =>'(UTC +13:00) Pacific/Apia',
                    'Pacific/Tongatapu' =>'(UTC +13:00) Pacific/Tongatapu',
                    'Pacific/Kiritimati' =>'(UTC +14:00) Pacific/Kiritimati'
);
return $timezone_list;
    }
}
