<?php
use \Codebird\Codebird;
class TwitterPost {

  public static function publishPost($publish_data) {
    $token_data = json_decode($publish_data['auth_detail'], true);
    $instance = \Codebird\Codebird::setConsumerKey($publish_data['api_key'], $publish_data['secret_key']);
    $twitter = \Codebird\Codebird::getInstance();
    $twitter->setToken($token_data['access_token'], $token_data['access_secret']);
    // $twitter->setUseCurl(false);
    $twitter->setTimeout(false);
    return self::Request($publish_data, $twitter);
  }

  private static function Request($publish_data, $twitter) {
    try {
      $result = $twitter->statuses_homeTimeline();
      if(isset($result->errors)){
        throw new Exception($result->errors[0]->message,$result->errors[0]->code);
      }
    }catch (Exception $e) {
      $response = array(
        'code' =>  $e->getCode(),
        'response' => $e->getMessage(),
        'status' => 'FAILED'
        );
      return $response;
    }
    try {
      if (!empty($publish_data['image_url'])) {
        $images_array = explode(',', $publish_data['image_url']);
        $media_ids = array();
        $i=1;
        foreach ($images_array as $image) {
          if($i==5) break;
          $image_local_path = public_path() . '/uploads/' . $image;
          $upload_request = $twitter->media_upload(array(
            'media' => $image_local_path
            ));
          if($upload_request->httpstatus == 200){
            $media_ids[] = $upload_request->media_id_string;
          }else{
            if($upload_request->httpstatus == 413){
              throw new Exception('One of the media file is too large.',413);
            }
          }
          $i++;
        }
        $media_ids = implode(',', $media_ids);
        if(!empty($publish_data['content'])){
          $limit = 116;
          $content = substr(str_replace("&nbsp;", '', strip_tags($publish_data['content'])), 0, $limit);
          $params = array(
            'status' => $content,
            'media_ids' => $media_ids
            );
        }else{
          $params = array(
            'media_ids' => $media_ids
            );
        }
      } 
      else {
        $limit = 139;
        $content = substr(str_replace("&nbsp;", '', strip_tags($publish_data['content'])), 0, $limit);
        $params = array(
          'status' => $content
          );
      }
      $reply = $twitter->statuses_update($params);
      if($reply->httpstatus==200){
        if(isset($reply->extended_entities->media)){
          foreach ($reply->extended_entities->media as $post_id) {
            $post_ids[] = $post_id->id; 
          }
          $response = array(
            'response' => implode(',', $post_ids),
            'status' => 'SUCCESS'
            );
        }else{
          $response = array(
            'response' => $reply->id,
            'status' => 'SUCCESS'
            );
        }
      }else{
        $response = array(
          'code' =>  $reply->errors[0]->code,
          'response' => $reply->errors[0]->message,
          'status' => 'FAILED'
          );
      }
    } catch (Exception $e) {
      $response = array(
        'response' => $e->getMessage(),
        'status' => 'FAILED'
        );
    }
    return $response;
  }
}
