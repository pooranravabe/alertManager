<?php

use LinkedIn\LinkedIn;


class LinkedInPost {

  public static function publishPost($publish_data) {
    $linkedin = new LinkedIn(array(
        'api_key' => $publish_data['api_key'],
        'api_secret' => $publish_data['secret_key'],
        'callback_url' => URL::to('auth/Linkedin'),
        ));
    $haouth_data = json_decode($publish_data['auth_detail']);
    $access_token = $haouth_data->access_token;
    $linkedin->setAccessToken($access_token);
    return self::Request($publish_data, $linkedin);
  }

  private static function Request($publish_data, $linkedin) {
    try {
      if (!empty($publish_data['image_url'])) {
        $limit = 699;
        $content = substr(str_replace("&nbsp;", '', strip_tags($publish_data['content'])), 0, $limit);
        $images_array = array();
        $images_array = explode(',',$publish_data['image_url']);
        // $image_url = url('/'). '/uploads/' . $images_array[0];
        $image_url = URL::asset('public/uploads/' . $images_array[0]);
        $post = array(
          'comment' => $content,
          'content' => array(
                      'submitted-image-url' => $image_url,
                      'submitted-url' => $image_url,
                      ),
          'visibility' => array(
          'code' => 'anyone'
            ));
      }else{
        if (!empty($publish_data['content'])) {
          $limit = 699;
          $content = substr(str_replace("&nbsp;", '', strip_tags($publish_data['content'])), 0, $limit);
          $post = array(
            'comment' => $content,
            'visibility' => array(
              'code' => 'anyone'
              )
            );
        }
      }
      $info = $linkedin->post('/people/~/shares', $post);
      $response = array(
        'response' => $info['updateKey'],
        'status' => 'SUCCESS'
        );
    } catch (Exception $ex) {
      $info = $linkedin->getDebugInfo();
      $r = explode('.',strtok($ex->getMessage(),"\n"));
      $response = array(
        'code'    => $info['http_code'],
        'response' => $r[0],
        'status' => 'FAILED'
        );
    }
    return $response;
  }

}