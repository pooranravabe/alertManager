<?php 

 class TumblrPost {

 	public static function publishPost($publish_data){
 		$oauth_data = json_decode($publish_data['auth_detail']);
 		$client = new Tumblr\API\Client($publish_data['api_key'], $publish_data['secret_key'],$oauth_data->access_token, $oauth_data->access_secret);
 		return self::Request($client->getUserInfo(),$publish_data, $client);
 	}

 	public static function Request($user_info, $publish_data, $client){
 		 try {
 		 	$data = array();
 		 	if(!empty($publish_data['content'])){
 		 		$data['title'] = '';
 		 		$data ['body']= $publish_data['content'];
 		 	}
 		 	if(!empty($publish_data['image_url'])){
 		 		$data['caption'] = $publish_data['content'];
 		 		$data['type'] = 'photo';
 		 		$publish_data['image_url'] = explode(',',$publish_data['image_url']);
 		 		foreach ($publish_data['image_url'] as $image_url) {
 		 			$image_data[] = URL::asset('uploads/'.$image_url);
 		 		}
 		 		$data['source'] = $image_data;
 		 	}
 		 	if(!empty($publish_data['video_url'])){
 		 		$data = array(
 		 			'caption' => $publish_data['content'],
		 			'type' => 'video',
		 			'data' => public_path() . '/uploads/' . $publish_data['video_url'],
	 			);
 		 	}
	      	$reply = $client->createPost($publish_data['name'], $data);
	 		$response = array(
	            'response' => $reply->id,
	            'status' => 'SUCCESS'
	            );
		    }catch (Exception $e) {
		      $response = array(
		        'code' =>  $e->getCode(),
		        'response' => $e->getMessage(),
		        'status' => 'FAILED'
		        );
		    }
		   return $response;
 	}
 }