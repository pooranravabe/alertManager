<?php
use \HieuLe\WordpressXmlrpcClient;
use HieuLe\WordpressXmlrpcClient\Exception;
class WordpressPost {
	public static function publishPost($publish_data) {
		$auth_detail =json_decode($publish_data['auth_detail']);
# The Monolog logger instance
		$wpLog = new Monolog\Logger('wp-xmlrpc');
# Create client instance
		$wpClient = new \HieuLe\WordpressXmlrpcClient\WordpressClient($auth_detail->site_url, $auth_detail->user_name, Crypt::decrypt($auth_detail->password));
		return self::postRequest($publish_data, $wpClient,$auth_detail);
  }
  private static function postRequest($publish_data, $wpClient,$auth_detail) {
  	try{
      if(!empty($publish_data['content'])){
        if(empty($publish_data['image_url']) && empty($publish_data['video_url'])){
          $content = array(
          'post_type'=>'post',
          'post_status' => 'publish', // http://codex.wordpress.org/Post_Status
          );
          $title = $publish_data['title'];
          $desc = $publish_data['content'];
        }
      }
      if (!empty($publish_data['image_url'])) {
        $publish_data['image_url'] = explode(',',$publish_data['image_url']);
        $title = $publish_data['title'];
        $i =1;
        $src ='';
        foreach($publish_data['image_url'] as $image){
          $image_url = public_path() . '/uploads/' . $image;
          $name = basename($image_url);
          $type = mime_content_type($image_url);
          $bits = file_get_contents($image_url);
          $post_image = $wpClient->uploadFile($name,$type,$bits);
          if($i==1){
           $content = array(
            'post_type'=>'post',
          'post_status' => 'publish', // http://codex.wordpress.org/Post_Status
          'post_thumbnail' => $post_image['id']
          );
           $i++;
         }else{
            $src .= "<img class='ravabe_image' src=".$post_image['url'].">";
          $i++;
        }
      }
      if(!empty($publish_data['content'])){
        $desc = $publish_data['content']." ".$src; 
      }else{
        $desc = $src;
      }
    }
    // if (!empty($publish_data['video_url'])) {
    //     $video_url = public_path() . '/uploads/' . $publish_data['video_url'];
    //      $name = basename($video_url);
    //       $type = mime_content_type($video_url);
    //       $bits = file_get_contents($video_url);
    //     $post_video = $wpClient->uploadFile($name,$type,$bits);
    //     $content = array(
    //       'post_type'=>'post',
    //       'post_status' => 'publish', // http://codex.wordpress.org/Post_Status
    //       );
    //     $title = $publish_data['title'];
    //     if(!empty($publish_data['content'])){
    //     $desc =$publish_data['content']."[video width='320' height='320' ".$post_video['type']."=".$post_video['url']."][/video]";
    //     }else{
    //       $desc = "[video width='320' height='320' ".$post_video['type']."=".$post_video['url']."][/video]";
    //     }
    //   }
      $user_post = $wpClient->newPost($title,$desc,$content);
  		$response = array(
          'response' => $user_post,
          'status' => 'SUCCESS'
          );
  	}catch(Exception\XmlrpcException $ex){
  		 $response = array(
        'code' => $ex->getCode(),
        'response' => $ex->getMessage(),
        'status' => 'FAILED'
        );
  	}catch(Exception\NetworkException $ex){
  		$response = array(
  			'code' => $ex->getCode(),
  			'response' => $ex->getMessage(),
  			'status' => 'FAILED'
  			);
  	}catch(Exception $ex){
      $response = array(
        'code' => $ex->getCode(),
        'response' => $ex->getMessage(),
        'status' => 'FAILED'
        );
    }
  	 return $response;
  }

}
?>
