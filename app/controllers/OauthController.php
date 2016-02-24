<?php

// Call set_include_path() as needed to point to your client library.
use \HieuLe\WordpressXmlrpcClient;
use HieuLe\WordpressXmlrpcClient\Exception;
require_once 'Google/Client.php';
require_once 'Google/Service/YouTube.php';
require_once 'Google/Service/Oauth2.php';
// use OAuth\OAuth1\Service\Tumblr;
// use OAuth\Common\Storage\Session;
// use OAuth\Common\Consumer\Credentials;

// class OauthController
class OauthController extends BaseController {

    public function __construct() {
        $this->beforeFilter('auth');
        if (Sentry::check()) {
            $this->user = Sentry::getUser();
        }
    }
    
// get Authorization
    public function getAuth($network) {
        $url = $this->$network();
        if (!empty($url)) {
            if (is_array($url)) {
                if($network == 'Tumblr'){
		return View::make('channel.channel-connect-success');
                  //return View::make('oauth/tumblr_blogs')->with(array('page_data' => $url));
                }else{
                  return View::make('oauth/facebook_pages')->with(array('page_data' => $url));
                } 
            } else {
                return Redirect::to((string) $url);
            }
        } else {
            return View::make('channel.channel-connect-success');
        }
    }
// Twitter Channel - get data from input
    public function twitter() {
        

        $token = Input::get('oauth_token');
        $verify = Input::get('oauth_verifier');
        // get twitter service
        $OAuth = new OAuth();
        $OAuth::setHttpClient('CurlClient');
        $tw = $OAuth::consumer('Twitter');
        // check if code is valid
        // if code is provided get user data and sign in
        if (!empty($token) && !empty($verify)) {
            // This was a callback request from twitter, get the token
            $token = $tw->requestAccessToken($token, $verify);
            $access_details = array(
              'access_token' => $token->getAccessToken(),
              'access_secret' => $token->getAccessTokenSecret()
              );
            // Send a request with it
            $result = json_decode($tw->request('account/verify_credentials.json'), true);
            $profile = $this->user->profile;
            if(!$profile->photo){
                try{
                    $new_name = uniqid() . '_' . time() . '.' .'png';
                    $image = Image::make($result['profile_image_url_https'])->save('./uploads/'.$new_name);
                    $profile->photo = $new_name;
                    $profile->save();
                }catch (Intervention\Image\Exception\NotReadableException $e) {

                }
            }
            try {
                $network_id = Network::select('id')->where('platform', '=', 'TWITTER')->first();
                $data = array(
                  'user_id' => $this->user->id,
                  'remote_id' => $result['id'],
                  'network_id' => $network_id->id,
                  'name' => $result['name'],
                  'auth_detail' => json_encode($access_details),
                  );
                $twitter_data = Channel::insert($data);
            } catch (Illuminate\Database\QueryException $ex) {
                $this->channelReConnect($result['id'],$access_details);
            }
        }
        // if not ask for permission first
        else {
            // get request token
            $reqToken = $tw->requestRequestToken();
            // get Authorization Uri sending the request token
            return $tw->getAuthorizationUri(array('oauth_token' => $reqToken->getRequestToken()));
        }
    }

// Facebook Channel
    public function facebook() {
        // get data from input
        $code = Input::get('code');
        // get fb service
        $OAuth = new OAuth();
        $OAuth::setHttpClient('CurlClient');
        $fb = $OAuth::consumer('Facebook');
        // check if code is valid
        // if code is provided get user data and sign in
        if (!empty($code)) {
            // This was a callback request from facebook, get the token
            $token = $fb->requestAccessToken($code);
            $access_details = array(
              'access_token' => $token->getAccessToken(),
              );
            // Send a request with it
            $result = json_decode($fb->request('/me'), true);
            $profile = $this->user->profile;
            if(!$profile->photo){
                $result_id = $result['id'];
                $new_name = uniqid() . '_' . time() . '.' .'png';
                try{
                  $image = Image::make("http://graph.facebook.com/$result_id/picture")->save('./uploads/'.$new_name);
                  $profile->photo = $new_name;
                  $profile->save();  
              }catch (Intervention\Image\Exception\NotReadableException $e) {

              }  
          }

          $facebook_pages = json_decode($fb->request('/me/accounts'), true);
          $page_data = $this->saveFacebookAccount($result, $access_details, $facebook_pages);
          if (!empty($page_data)) {
            return $page_data;
          }
    }
        // if not ask for permission first
    else {
            // get fb authorization url
        return $fb->getAuthorizationUri();
    }
}

// Linkedin Channel
public function linkedin() {
        // get data from input
    $code = Input::get('code');
    $OAuth = new OAuth();
    $OAuth::setHttpClient('CurlClient');
    $linkedinService = $OAuth::consumer('Linkedin');
    if (!empty($code)) {
            // This was a callback request from linkedin, get the token
        $token = $linkedinService->requestAccessToken($code);
        $access_details = array(
          'access_token' => $token->getAccessToken(),
          );
            // Send a request with it. Please note that XML is the default format.
        $result = json_decode($linkedinService->request('/people/~?format=json'), true);
        $profile = $this->user->profile;
        if(!$profile->photo){
            try{
                $picture_url = json_decode($linkedinService->request('/people/~:(picture-url)?format=json'),true);
                $new_name = uniqid() . '_' . time() . '.' .'png';
                if($picture_url){
                    $image = Image::make($picture_url['pictureUrl'])->save('./uploads/'.$new_name);
                    $profile->photo = $new_name;
                    $profile->save();
                }  
            }catch (Intervention\Image\Exception\NotReadableException $e) {

            }
        }
        $companies = json_decode($linkedinService->request("https://api.linkedin.com/v1/companies?format=json&is-company-admin=true"), true);
        $linkedin_page_data = $this->saveLinkedInAccount($result, $access_details, $companies);
        if (!empty($linkedin_page_data)) {
            return $linkedin_page_data;
        }
        }// if not ask for permission first
        else {
            // get linkedinService authorization
            return $linkedinService->getAuthorizationUri(array('state' => 'DCEEFWF45453sdffef424'));
        }
    }

// Flickr Channel
    public function flickr() {
        $token = Input::get('oauth_token');
        $verify = Input::get('oauth_verifier');
        $OAuth = new OAuth();
        $OAuth::setHttpClient('CurlClient');
        $flikrService = $OAuth::consumer('Flickr');
        if (!empty($token) && !empty($verify)) {
            // This was a callback request from linkedin, get the token
            $token_data = $flikrService->requestAccessToken($token, $verify);
            $access_details = array(
              'access_token' => $token_data->getAccessToken(),
              'access_secret' => $token_data->getAccessTokenSecret()
              );
            $result = $token_data->getExtraParams();
            try {
                $network_id = Network::select('id')->where('platform', '=', 'FLICKR')->first();
                $data = array(
                  'user_id' => $this->user->id,
                  'remote_id' => $result['user_nsid'],
                  'network_id' => $network_id->id,
                  'name' => $result['fullname'],
                  'auth_detail' => json_encode($access_details),
                  );
                $flikr_data = Channel::insert($data);
            } catch (Illuminate\Database\QueryException $ex) {
                $this->channelReConnect($result['user_nsid'],$access_details);
            }
        }// if not ask for permission first
        else {
            // get request token
            $reqToken = $flikrService->requestRequestToken();
            // get Authorization Uri sending the request token
            return $flikrService->getAuthorizationUri(array('oauth_token' => $reqToken->getRequestToken()));
        }
    }
// saveFacebookAccount function
    private function saveFacebookAccount($result, $token, $facebook_pages) {
        try {
            $network_id = Network::select('id')->where('platform', '=', 'FACEBOOK')->first();
            $data = array(
              'user_id' => $this->user->id,
              'remote_id' => $result['id'],
              'network_id' => $network_id->id,
              'name' => $result['first_name'] . ' ' . $result['last_name'],
              'auth_detail' => json_encode($token),
              );
            $facebook_data = Channel::insert($data);
        } catch (Illuminate\Database\QueryException $ex) {
            $this->channelReConnect($result['id'],$token);
        }
        if (empty($facebook_pages)) {
            return false;
        } else {
            return $this->processFacebookPages($facebook_pages);
        }
    }
  
  // processFacebookPages function
    private function processFacebookPages($facebook_pages) {
        $page_data = array();
        $i = 0;
        $network_id = Network::select('id')->where('platform', '=', 'FACEBOOK_PAGE')->first();
        foreach ($facebook_pages['data'] as $facebook_page) {
            $page_auth_data = $facebook_page;
            $page_auth_data['type'] = 'FACEBOOKPAGE';
            $page_data[$i]['account'] = array(
              'remote_id' => $facebook_page['id'],
              'network_id' => $network_id->id,
              'user_id' => $this->user->id,
              'name' => $page_auth_data['name'],
              'auth_detail' => json_encode($page_auth_data),
              );
            $i++;
        }
        return $page_data;
    }

   // postFacebookPages function
    public function postFacebookPages() {
        $pages = Input::all();
        if (isset($pages['save'])) {
            if(isset($pages['fb_pages'])){
              foreach ($pages['fb_pages'] as $page) {
                try {
                    $page_data = Channel::insert(json_decode($page, true));
                } catch (Illuminate\Database\QueryException $ex) {
                    $page = json_decode($page, true);
                    $this->channelReConnect($page['remote_id'],json_decode($page['auth_detail']));
                }

            }  
        }
    }
    return View::make('channel.channel-connect-success');
}

// saveLinkedInAccount function
public function saveLinkedInAccount($result, $access_details, $companies){
    try {
        $network_id = Network::select('id')->where('platform', '=', 'LINKEDIN')->first();
        $data = array(
          'user_id' => $this->user->id,
          'remote_id' => $result['id'],
          'network_id' => $network_id->id,
          'name' => $result['firstName'] . ' ' . $result['lastName'],
          'auth_detail' => json_encode($access_details),
          );
        $linkedin_data = Channel::insert($data);
    } catch (Illuminate\Database\QueryException $ex) {
        $this->channelReConnect($result['id'],$access_details);
    }
    if ($companies['_total']==0) {
        return false;
    } else {
        return $this->processLinkedInCompanyPages($companies,$access_details);
    }
}

// processLinkedInCompanyPages function
private function processLinkedInCompanyPages($companies,$access_details) {
    $page_data = array();
    $i = 0;
    $network_id = Network::select('id')->where('platform', '=', 'LINKEDIN_COMPANY')->first();
    foreach ($companies['values'] as $company) {
        $page_data[$i]['account'] = array(
          'remote_id' => $company['id'],
          'network_id' => $network_id->id,
          'user_id' => $this->user->id,
          'name' => $company['name'],
          'auth_detail' => json_encode($access_details),
          );
        $i++;
    }
    return $page_data;
}

// YouTube Channel
public function youtube() {
    $auth_data = Network::getCredentials('YOUTUBE', 'withscope');
    $client = new Google_Client();
    $client->setClientId($auth_data['client_id']);
    $client->setClientSecret($auth_data['client_secret']);
    $client->setScopes(array($auth_data['scope']));
    $client->setApplicationName('HBRAVABE');
    $client->setRedirectUri(Config::get('app.url') . 'auth/Youtube');
    $client->setAccessType('offline');
    $client->setApprovalPrompt("force");
    $oauth2 = new Google_Service_Oauth2($client);
    $code = Input::get('code');
    if (!empty($code)) {
        $user_info = array();
        $client->authenticate($code);
        $token = $client->getAccessToken();
        $authObj = json_decode($token);
        $accessToken = $authObj->access_token;
        if (isset($accessToken)) {
            $user_info = $oauth2->userinfo->get();
        }
        try {
            $network_id = Network::select('id')->where('platform', '=', 'YOUTUBE')->first();
            $data = array(
              'user_id' => $this->user->id,
              'remote_id' => $user_info->id,
              'network_id' => $network_id->id,
              'name' => $user_info->name,
              'auth_detail' => $token,
              );
            $youtube_data = Channel::insert($data);
            $profile = $this->user->profile;
            if(!$profile->photo){
                try{
                    $new_name = uniqid() . '_' . time() . '.' .'png';
                    $image = Image::make($user_info['picture'])->save('./uploads/'.$new_name);
                    $profile->photo = $new_name;
                    $profile->save();
                }catch (Intervention\Image\Exception\NotReadableException $e) {

                }
            }
        } catch (Illuminate\Database\QueryException $ex) {
            $this->channelReConnect($user_info->id,$authObj);
        }
    } else {
        return $client->createAuthUrl();
    }
}
// WordPress function
public function wordpress() {
   return URL::to('wp-login');

}
// Wordpress getlogin function
public function getWpLogin(){
    return view::make('oauth/wordpress');
}
// Wordpress postlogin function
public function postWpLogin(){
    $site_url= Input::get('site_url');
    $user_name= Input::get('user_name');
    $password= Input::get('password');
    $displayname= Input::get('display_name');
    try{
       // Your Wordpress website is at: http://wp-website.com
        $endpoint = $site_url.'/xmlrpc.php';
# The Monolog logger instance
        $wpLog = new Monolog\Logger('wp-xmlrpc');
# Create client instance
        $wpClient = new \HieuLe\WordpressXmlrpcClient\WordpressClient($endpoint, $user_name, $password);
        $user_profile = $wpClient->getProfile();
        if(!empty($user_profile['roles'])){
            if($user_profile['roles'][0]=="administrator"||$user_profile['roles'][0]=="author"){
                $access_details = array(
                  'user_name' =>$user_name,
                  'password' => Crypt::encrypt($password),
                  'site_url' => $endpoint
                  );
                $network_id = Network::select('id')->where('platform', '=', 'WORDPRESS')->first();
                $remote_id =$user_profile['user_id'].'_'.$endpoint;
                $data = array(
                  'user_id' => $this->user->id,
                  'remote_id' => $remote_id,
                  'network_id' => $network_id->id,
                  'name' => $displayname,
                  'auth_detail'=> json_encode($access_details)
                  );
                $data = Channel::insert($data);
                return View::make('channel.channel-connect-success');
            }else{
               Toastr::error('you have not permission to post');
               return Redirect::to('wp-login'); 
           }
       }else{
           Toastr::error('you have not permission to post');
           return Redirect::to('wp-login'); 
       }
   }catch(Exception\XmlrpcException $ex){
    Toastr::error($ex->getMessage(), $title = null, $options = []);
    return Redirect::to('wp-login');
}catch(Exception\NetworkException $ex){
    if($ex->getCode()==404){
        Toastr::error('Url Not Found', $title = null, $options = []);
    }elseif ($ex->getCode()==403) {
        Toastr::error('Access Forbidden', $title = null, $options = []);
    }else{
        Toastr::error('Something Went Wrong', $title = null, $options = []);
    }
    return Redirect::to('wp-login');
}catch (Illuminate\Database\QueryException $ex) {
 $channel = Channel::where('user_id', '=', $this->user->id)->where('remote_id', $remote_id)->first();
 $channel->auth_detail = json_encode($access_details);
 $channel->name = $displayname;
 $channel->need_reconnect = 0;
 $channel->save();
 return View::make('channel.channel-connect-success');
}
}
// channelReConnect function
public function channelReConnect($remote_id, $access_details) {
    $channel = Channel::where('user_id', '=', $this->user->id)->where('remote_id', $remote_id)->first();
    $channel->auth_detail = json_encode($access_details);
    $channel->need_reconnect = 0;
    $channel->save();
}

// Tumblr Channel
public function tumblr(){
    $oauth_token = Input::get('oauth_token');
    $oauth_verifier = Input::get('oauth_verifier');
    $tumblr = OAuth::consumer('Tumblr', URL::to('auth/Tumblr'));
    if (!empty($oauth_token) && !empty($oauth_verifier)) {
        $token_data = $tumblr->requestAccessToken($oauth_token, $oauth_verifier);
        $result = json_decode( $tumblr->request('user/info'), true );
        $network = Network::select('id')->where('platform', 'TUMBLR')->first();
        $access_details = array(
              'access_token' => $token_data->getAccessToken(),
              'access_secret' => $token_data->getAccessTokenSecret()
              );
        foreach ($result['response']['user']['blogs'] as $blogs) {
            $channel_exists = Channel::where('remote_id', $result['response']['user']['blogs'][0]['url'])->get();
            if($channel_exists->count()){
              continue;
            } 
            $data = array(
                      'user_id' => $this->user->id,
                      'remote_id' => $result['response']['user']['blogs'][0]['url'],
                      'network_id' => $network->id,
                      'name' => $result['response']['user']['name'],
                      'auth_detail'=> json_encode($access_details)
                    );
              $tumblr_data = Channel::insert($data);
          }
          $Channel_data = Channel::where('user_id', $this->user->id)->where('network_id', $network->id)->get();
          $client = new Tumblr\API\Client($oauth_token, $oauth_verifier,$access_details['access_token'], $access_details['access_secret']);
          $blogs_404 = [];
          foreach ($Channel_data as $Channel) {
            try {
              $blogs = $client->getBlogInfo($Channel->name);
              $blogs_404['exist'][] = array('id' => $Channel->id,'name' => $Channel->name);
            } catch (Tumblr\API\RequestException $e) {
              $blogs_404['non-exist'][] = array('id' => $Channel->id,'name' => $Channel->name);
            } 
          }
          if (!empty($blogs_404['non-exist'])) {
              return $blogs_404;
          }
    }else {
        $token = $tumblr->requestRequestToken();
        return $tumblr->getAuthorizationUri(array('oauth_token' => $token->getRequestToken()));
    }
}

}