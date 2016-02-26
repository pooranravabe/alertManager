<?php

class Publishstatus extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'publish_status';
    protected $fillable = array('id', 'content_id', 'channel_id', 'status', 'response', 'updated_at', 'created_at');

    public function channel() {
        return $this->belongsTo('Channel', 'channel_id');
    }

    public function content() {
        return $this->belongsTo('Content', 'content_id');
    }
    
    public function channel_ids() {
        return $this->hasMany('Publishstatus', 'content_id');
    }
	public function project_ids() {
        return $this->hasMany('Project', 'project_ids');
    }
    
    //Get all content ids from user's id @SUMIT
	public static function getNetworkIds($user_id){
		$published_networkid_details = array();
		$project_content_ids = array();
		$project_channel_ids = array();
		$project_network_ids = array();
		$response_ids = array();
		$project_ids = array();
		$content_ids = array();
		$channel_ids = array();
		$network_ids = array();
		$auth_ids= array();
		$projectdetails = Project::where('user_id', $user_id)->get();
		foreach($projectdetails as $projectdetail) {
			$project_ids[]= $projectdetail->id; 
        }
		foreach($project_ids as $project_id) {
			$contentdetails = Content::where('project_id', $project_id)->where('status', 'PUBLISHED')->get();
			foreach($contentdetails as $contentdetail) {
				$content_ids[]= $contentdetail->id; 
			}
			$project_content_ids[]=array(
				$project_id=> $content_ids
			);
			$content_ids = array();
        }
		foreach($project_content_ids as $project_content_id) {
			foreach($project_content_id as $contentarray) {
				foreach($contentarray as $content_id){
					$channeldetails = Publishstatus::where('content_id', $content_id)->where('status', 'SUCCESS')->get();
					foreach($channeldetails as $channeldetail) {
						$channel_ids[]= $channeldetail->channel_id.'*'.$channeldetail->response.'*'.$content_id.'*'.$channeldetail->updated_at;
						$response_ids[]=$channeldetail->response;
					}
				}
				$project_channel_ids[]=array(
					key($project_content_id)=> $channel_ids,
					'response'=> $response_ids
					);
					$channel_ids = array();
					$response_ids = array();
			}
        }
		
		foreach($project_channel_ids as $project_channel_id) {
			$track=0;
			foreach($project_channel_id as $project_channels){
				if($track==0){
				foreach($project_channels as $project_channel){
					$channelid_response=explode("*", $project_channel);
					$networkiddetails = Channel::where('id', $channelid_response[0])->get();
					foreach($networkiddetails as $networkiddetail) {
						$network_ids[]= $networkiddetail->network_id;
						$auth_ids[]= $networkiddetail->auth_detail;
						
					}
				}
				$project_network_ids[]=array(
					key($project_channel_id)=> $network_ids,
					'auth_detail'=> $auth_ids
					);
					$network_ids = array();
					$auth_ids = array();
			}$track++;}
			
		}
		
        /*$contentids = Content::where('author_id', $author_id)->where('status', 'PUBLISHED')->get();
		foreach($contentids as $contentid) {
			$Publishstatus = Publishstatus::where('content_id', $contentid->id)->where('status', 'SUCCESS')->get();	
        }*/
			$published_networkid_details[]=array(
			'project_ids' => $project_ids,
			'project_content_ids' => $project_content_ids,
			'project_channel_ids' => $project_channel_ids,
			'project_network_ids' => $project_network_ids
			);
           return $published_networkid_details;
    }
    
    public static function getResponseIds($projects){
		$published_networkid_details = array();
		$project_content_ids = array();
		$project_channel_ids = array();
		$project_network_ids = array();
		$response_ids = array();
		//$project_ids = array();
		$content_ids = array();
		$channel_ids = array();
		$network_ids = array();
		$auth_ids= array();
		//$projectdetails = Project::where('user_id', $user_id)->get();
		/*foreach($projectdetails as $projectdetail) {
			$project_ids[]= $projectdetail->id; 
        }*/
		foreach($projects as $project_type) {
			foreach($project_type as $project_id) {
				$contentdetails = Content::where('project_id', $project_id)->where('status', 'PUBLISHED')->get();
				foreach($contentdetails as $contentdetail) {
					$content_ids[]= $contentdetail->id; 
				}
				$project_content_ids[]=array(
					$project_id=> $content_ids
				);
				$content_ids = array();
			}	
        }
		foreach($project_content_ids as $project_content_id) {
			foreach($project_content_id as $contentarray) {
				foreach($contentarray as $content_id){
					$channeldetails = Publishstatus::where('content_id', $content_id)->where('status', 'SUCCESS')->get();
					foreach($channeldetails as $channeldetail) {
						$channel_ids[]= $channeldetail->channel_id.'*'.$channeldetail->response.'*'.$content_id.'*'.$channeldetail->updated_at;
						$response_ids[]=$channeldetail->response;
					}
				}
				$project_channel_ids[]=array(
					key($project_content_id)=> $channel_ids,
					'response'=> $response_ids
					);
					$channel_ids = array();
					$response_ids = array();
			}
        }
		
		foreach($project_channel_ids as $project_channel_id) {
			$track=0;
			foreach($project_channel_id as $project_channels){
				if($track==0){
				foreach($project_channels as $project_channel){
					$channelid_response=explode("*", $project_channel);
					$networkiddetails = Channel::where('id', $channelid_response[0])->get();
					foreach($networkiddetails as $networkiddetail) {
						$network_ids[]= $networkiddetail->network_id;
						$auth_ids[]= $networkiddetail->auth_detail;
						
					}
				}
				$project_network_ids[]=array(
					key($project_channel_id)=> $network_ids,
					'auth_detail'=> $auth_ids
					);
					$network_ids = array();
					$auth_ids = array();
			}$track++;}
			
		}
		
        /*$contentids = Content::where('author_id', $author_id)->where('status', 'PUBLISHED')->get();
		foreach($contentids as $contentid) {
			$Publishstatus = Publishstatus::where('content_id', $contentid->id)->where('status', 'SUCCESS')->get();	
        }*/
			$published_networkid_details[]=array(
			'project_ids' => $projects,
			'project_content_ids' => $project_content_ids,
			'project_channel_ids' => $project_channel_ids,
			'project_network_ids' => $project_network_ids
			);
           return $published_networkid_details;
    }
	
	public static function getContentIdsWithDate($projects,$from,$to){
		$published_networkid_details = array();
		$project_content_ids = array();
		$project_channel_ids = array();
		$project_network_ids = array();
		$response_ids = array();
		//$project_ids = array();
		$content_ids = array();
		$channel_ids = array();
		$network_ids = array();
		$auth_ids= array();
		//$projectdetails = Project::where('user_id', $user_id)->get();
		/*foreach($projectdetails as $projectdetail) {
			$project_ids[]= $projectdetail->id; 
        }*/
		foreach($projects as $project_type) {
			foreach($project_type as $project_id) {
				//$contentdetails = Content::where('project_id', $project_id)->where('status', 'PUBLISHED')->whereBetween('created_at', array($from, $to))->get();
				$contentdetails = Content::where('project_id', $project_id)->where('status', 'PUBLISHED')->get();
				foreach($contentdetails as $contentdetail) {
					$content_ids[]= $contentdetail->id; 
				}
				$project_content_ids[]=array(
					$project_id=> $content_ids
				);
				$content_ids = array();
			}	
        }
		foreach($project_content_ids as $project_content_id) {
			foreach($project_content_id as $contentarray) {
				foreach($contentarray as $content_id){
					$channeldetails = Publishstatus::where('content_id', $content_id)->where('status', 'SUCCESS')->get();
					foreach($channeldetails as $channeldetail) {
						$channel_ids[]= $channeldetail->channel_id.'*'.$channeldetail->response.'*'.$content_id.'*'.$channeldetail->updated_at;
						$response_ids[]=$channeldetail->response;
					}
				}
				$project_channel_ids[]=array(
					key($project_content_id)=> $channel_ids,
					'response'=> $response_ids
					);
					$channel_ids = array();
					$response_ids = array();
			}
        }
		
		foreach($project_channel_ids as $project_channel_id) {
			$track=0;
			foreach($project_channel_id as $project_channels){
				if($track==0){
				foreach($project_channels as $project_channel){
					$channelid_response=explode("*", $project_channel);
					$networkiddetails = Channel::where('id', $channelid_response[0])->get();
					foreach($networkiddetails as $networkiddetail) {
						$network_ids[]= $networkiddetail->network_id;
						$auth_ids[]= $networkiddetail->auth_detail;
						
					}
				}
				$project_network_ids[]=array(
					key($project_channel_id)=> $network_ids,
					'auth_detail'=> $auth_ids
					);
					$network_ids = array();
					$auth_ids = array();
			}$track++;}
			
		}
		
        /*$contentids = Content::where('author_id', $author_id)->where('status', 'PUBLISHED')->get();
		foreach($contentids as $contentid) {
			$Publishstatus = Publishstatus::where('content_id', $contentid->id)->where('status', 'SUCCESS')->get();	
        }*/
			$published_networkid_details[]=array(
			'project_ids' => $projects,
			'project_content_ids' => $project_content_ids,
			'project_channel_ids' => $project_channel_ids,
			'project_network_ids' => $project_network_ids
			);
           return $published_networkid_details;
    }
	
	public static function checkUnknownIds($feed_id){
		$unknown = Publishstatus::where('response', $feed_id)->where('status', 'SUCCESS')->first();
		if($unknown){
			return false;
		}
		return true;
	}
}
