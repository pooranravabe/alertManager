<?php
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphObject;
use Facebook\FacebookRequestException;

class FacebookPost {

  public static function publishPost($publish_data) {
    $config = array();
    $config['appId'] =$publish_data['api_key'];
    $config['secret'] = $publish_data['secret_key'];
    $config['fileUpload'] = true;
    $config['cookie'] = true;
    $facebook = FacebookSession::setDefaultApplication($config['appId'], $config['secret']);
    $haouth_data = json_decode($publish_data['auth_detail']);
    $fb_session = new FacebookSession($haouth_data->access_token);
    return self::postRequest($publish_data, $fb_session);
  }

  private static function postRequest($publish_data, $fb_session) {
    if ($fb_session) {
      try {
        $me = (new FacebookRequest(
          $fb_session, 'GET', '/me'))->execute()->getGraphObject();
      } catch (FacebookRequestException $e) {
        $message =
        "";
        if($e->getResponse()['error']['code']==190){
          $message = "Unable to connect Facebook account, check your social networks and resend. error: User has not authorized";
        }else{
            $message = $e->getResponse()['error']['message'];
        }
        $response = array(
          'code' =>  $e->getResponse()['error']['code'],
          'response' => $message,
          'status' => 'FAILED'
          );
         return $response;

      }
      try {
        $files = array();
        if(!empty($publish_data['content'])){
          $limit = 5000;
          $content = substr(str_replace("&nbsp;", '', strip_tags($publish_data['content'])), 0, $limit);
          if(empty($publish_data['image_url']) && empty($publish_data['video_url'])){
            $reqests_queue[] = array(
              "method"  => "POST",
              "relative_url"  => "me/feed", 
              "body" => "message=".urlencode($content),
              );
          }
        }
        if (!empty($publish_data['image_url'])) {
          $publish_data['image_url'] = explode(',',$publish_data['image_url']);
          $i =1;
          foreach($publish_data['image_url'] as $image){
            $image_url = public_path() . '/uploads/' . $image;
            $files['image_'.$i] = new CurlFile($image_url);
            if(!empty($publish_data['content']) && $i==1){
              $limit = 5000;
              $content = substr(str_replace("&nbsp;", '', strip_tags($publish_data['content'])), 0, $limit);
              $reqests_queue[] = array(
                "method"  => "POST",
                "relative_url"  => "me/photos", 
                "attached_files" => 'image_'.$i,
                "body" => "message=".urlencode($content)
                );
              $i++;
            }else{
              $reqests_queue[] = array(
                "method"  => "POST",
                "relative_url"  => "me/photos", 
                "attached_files" => 'image_'.$i,
                );
              $i++;
            }
          }
        }
        if (!empty($publish_data['video_url'])) {
          $limit = 5000;
          $content = substr(str_replace("&nbsp;", '', strip_tags($publish_data['content'])), 0, $limit);
          $files['video'] = new CurlFile(public_path() . '/uploads/' . $publish_data['video_url']);
          if(!empty($publish_data['content'])){
           $reqests_queue[] = array(
            "method"  => "POST",
            "relative_url"  => "me/videos", 
            "attached_files" => 'video',
            "body" => "description=".urlencode($content)
            );
         }
         else{
          $reqests_queue[] = array(
            "method"  => "POST",
            "relative_url"  => "me/videos", 
            "attached_files" => 'video',
            );
        }
      }
      $fb_request = new FacebookRequest($fb_session, 'POST', '?batch='.urlencode(json_encode($reqests_queue)), $files);
      $response = $fb_request->execute()->getGraphObject();
      $errors = array();
      $post_ids = array();
      foreach ($response->asArray() as $status) {
        if($status->code != 200){
          $res = json_decode($status->body);
          $errors[] = $res->error->message;
        }else{
          $res = json_decode($status->body);
          $post_ids[] = $res->id;
        }
      }
      if(!empty($errors)){
        $response = array(
          'response' => implode(',', $errors),
          'status' => 'FAILED'
          );
      }else{
        $response = array(
          'response' => implode(',', $post_ids),
          'status' => 'SUCCESS'
          );
      }
    }catch (FacebookRequestException $e) {
      $response = array(
        'code' => $e->getSubErrorCode(),
        'response' => $e->getResponse()->message,
        'status' => 'FAILED'
        );
    } 
    catch (Exception $e) {
      $response = array(
        'code' => $e->getCode(),
        'response' => $e->getMessage(),
        'status' => 'FAILED'
        );
    }
    return $response;
  }
}
}

?>
