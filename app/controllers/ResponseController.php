<?php
//require_once 'C:/xampp/htdocs/ravabe/vendor/autoload.php';
//require_once 'Google/Client.php';
//require_once 'Google/Service/YouTube.php';
//require_once 'Google/Http/MediaFileUpload.php';
class ResponseController extends BaseController {
	public $userid;
	public $email;
	public $filepath ;
	public $Folder;
	
	
    public function __construct() {
        $this->beforeFilter('auth');
        parent::__construct();
        if (Sentry::check()) {
            $this->user = Sentry::getUser();
			$this->userid = $this->user->id;		   
		   $tmp = DB::table('alert_admin')->select('email')->where('id','=', $this->user->id)->first();
		   $this->email = $tmp->email;
        }
	   
		$this->Folder 		    = public_path().'/user/'.$this->userid;	   
		$this->filepath 		  = $this->Folder.'/jsons/';
		
		$old = umask(0);
		File::makeDirectory($this->filepath, 0755, true, true);
		umask($old);
    }
	
	public function getResponse(){
		// url location
        	$Chklolcation =  new checkLocations();
		$Chklolcation->saveinfo();
        $name = "Hello!";
		$userid=$this->user->id;
		$Response_details = array();
		$Response_networkwise = array();
		$NetworkIds = Publishstatus::getNetworkIds($this->user->id);
		$tf_like=0;$tt_like=0;$tl_like=0;$ty_like=0;
		$tf_share=0;$tt_share=0;$tl_share=0;$ty_share=0;
		$tf_comment=0;$tt_comment=0;$tl_comment=0;$ty_comment=0;
		$tf_project=array();$tt_project=array();$tl_project=array();$ty_project=array();
		//echo"<pre>";
		//var_dump($NetworkIds[0]);exit;
		if(count($NetworkIds[0]['project_ids'])!=0){
			foreach($NetworkIds[0]['project_ids'] as $pkey=>$project){
				$f_like=0;$t_like=0;$l_like=0;$y_like=0;
				$f_share=0;$t_share=0;$l_share=0;$y_share=0;
				$f_comment=0;$t_comment=0;$l_comment=0;$y_comment=0;
				$f_network=0;$t_network=0;$l_network=0;$y_network=0;
				foreach($NetworkIds[0]['project_network_ids'][$pkey][$project] as $key => $network){
					if($network==1){
						$responseObj = $NetworkIds[0]['project_channel_ids'][$pkey]['response'][(int)$key];
						$token=$NetworkIds[0]['project_network_ids'][$pkey]['auth_detail'][(int)$key];
						//Here get the likes
						$likesurl="https://graph.facebook.com/likes?id=".$responseObj."&access_token=";
                        $accessToken=(json_decode($token)->access_token);
                        $likesurl_with_token = $likesurl.$accessToken ;
						//$context = @file_get_contents($likesurl_with_token);
						
						$context = '';
						$mod_date= filemtime($this->filepath.$accessToken."likewithjson.txt");					
						$now_date=strtotime('+1 hour');
						if(file_exists($this->filepath.$accessToken."likewithjson.txt")) {
							if($now_date>$mod_date) {
								$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
								$context = @file_get_contents($likesurl_with_token);
								fwrite($Userfile, $context);
								fclose($Userfile);
							} else {
									$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "r");
									$context = file_get_contents($Userfile);
									fclose($Userfile);
							}
						} else {
							$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
							$context = @file_get_contents($likesurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						}
						
						//var_dump($context);
						if($context!=FALSE){
							$jsonlikes = file_get_contents($likesurl_with_token);
							$likesResponses=json_decode($jsonlikes, true);
							//echo"<pre>";
							//var_dump($likesResponses);
							$userLikes = "";
							$likescount=2;
							$totalcount=0;
							if($likesResponses['data']!=null && count($likesResponses)>=2){
								$totalcount=count($likesResponses['data']);
							}
							//var_dump($totalcount);
							$f_like+=$totalcount;
							$tf_like+=$totalcount;
							$f_network=1;
						}
						//shared post
                        $data=explode('_', $responseObj);
						$p_id=(count($data)>1)?$data[1]:$data[0];
						$sharedurl="https://graph.facebook.com/".$p_id."/sharedposts?access_token=";
						//$accessToken=(json_decode($channelInfo->auth_detail)->access_token);
                        $sharedurlurl_with_token = $sharedurl.$accessToken ;
                        $context = @file_get_contents($sharedurlurl_with_token);
						if($context!=FALSE){
							$jsonshares = file_get_contents($sharedurlurl_with_token);
							$sharesResponses=json_decode($jsonshares, true);
							//echo"<pre>";
							//var_dump(count($sharesResponses['data']));
							$f_share+=count($sharesResponses['data']);
							$tf_share+=count($sharesResponses['data']);
							$f_network=1;
							$sharedhistory = array();
							if($sharesResponses['data']!=null && count($sharesResponses)>=2){
								foreach($sharesResponses['data'] as $sharesResponse){
									$sharedhistory[]= array(
										'story' => $sharesResponse['story'], 
                                    );
								}
							}
							else{
                             $sharedhistory[]= array(
                                    'story' =>'no any share your post.', 
                                );
							}
						}
						//Here get the comments
						$commenturl="https://graph.facebook.com/".$responseObj."/comments?summary=true&access_token=";
						$commenturl_with_token = $commenturl.$accessToken ;
						
						$json = '';
						$mod_date= filemtime($this->filepath.$accessToken."graphfacebook.txt");					
						$now_date=strtotime('+1 hour');
						if(file_exists($this->filepath.$accessToken."graphfacebook.txt")) {
							if($now_date>$mod_date) {
								$Userfile = fopen($this->filepath.$accessToken."graphfacebook.txt", "w");
								$json = @file_get_contents($commenturl_with_token);
								fwrite($Userfile, $json);
								fclose($Userfile);
							} else {
									$Userfile = fopen($this->filepath.$accessToken."graphfacebook.txt", "r");
									$json = file_get_contents($Userfile);
									fclose($Userfile);
							}
						} else {
							$Userfile = fopen($this->filepath.$accessToken."graphfacebook.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
							fwrite($Userfile, $json);
							fclose($Userfile);
						}
						
						if($json!=FALSE){
							$commentResponses=json_decode($json, true);
							//echo "<pre>";
							//var_dump($commentResponses);exit;
							$conact=explode('*', $NetworkIds[0]['project_channel_ids'][$pkey][$project][(int)$key]);
							if(count($commentResponses)>0){
								if(count($commentResponses['data'])>0){
									foreach($commentResponses['data'] as $cm_key=>$commentInfo){
										$responseofOne=array(
											'response' => $commentInfo['id'],
											'text' => $commentInfo['message'],
											'content_id' =>$conact[2],
											'content_reponse' => $responseObj,
											'project_id' => $project,
											'network_id' => $network,
											'channel_id' =>$conact[0],
											'user_id' => $userid,
											'commented_by_name' => $commentInfo['from']['name'],
											'commented_by_id' => $commentInfo['from']['id'],
											//'comment_time' => (string)$commentInfo['created_time'],
											'likes_on_comment' => $commentInfo['like_count']
										);
										$FBinsert=ResponseComments::saveResponses($responseofOne);
										//echo"<pre>";var_dump($zzz);exit;
									}
								}
								
							}
							if(count($commentResponses)>1){
								$f_comment+=$commentResponses['summary']['total_count'];
								$tf_comment+=$commentResponses['summary']['total_count'];
							}
							$f_network=1;
							//var_dump($f_comment);exit;
						}
						//var_dump("Facebook:".$f_like.' | '.$f_share);
					}
					elseif($network==2){
						$networkInfo = DB::table('network')->where('id', $network)->first();
						$CONSUMER_KEYs = $networkInfo->api_key;
						$CONSUMER_SECRETs = $networkInfo->secret_key;
						$token=$NetworkIds[0]['project_network_ids'][$pkey]['auth_detail'][(int)$key];
						if ($token != null) {
							$responseObj = $NetworkIds[0]['project_channel_ids'][$pkey]['response'][(int)$key];
							if ($responseObj != null) {
								$accessToken = (json_decode($token)->access_token);
								$accesssecret = (json_decode($token)->access_secret);
								\Codebird\Codebird::setConsumerKey($CONSUMER_KEYs, $CONSUMER_SECRETs);
								$cb = \Codebird\Codebird::getInstance();
								$cb->setToken($accessToken, $accesssecret);
								$reply = $cb->statuses_show_ID('id=' . $responseObj);
								$rereply = (array)$cb->statuses_mentionsTimeline();
								$tweetreply = array();
								foreach ($rereply as $rep) {
									//echo"<pre>";var_dump($rep->favorite_count);exit;
									if (count((array)$rep) > 3 && $responseObj == $rep->in_reply_to_status_id_str) {
										$tweetreply[] = array(
											'reply_text' => $rep->text,
											'twittes_id' => $rep->id_str,
											'twittes_screen_name' => $rep->user->name,
											'twittes_screen_id' => $rep->user->id,
											'reply_time' => $rep->created_at,
											'reply_favorite_count' => $rep->favorite_count
										);
									}
								}
								//var_dump($tweetreply);exit;
								//var_dump("Twitter:".$t_like.' | '.$t_share);
								$conact=explode('*', $NetworkIds[0]['project_channel_ids'][$pkey][$project][(int)$key]);
								if(count($tweetreply)>0){
									foreach($tweetreply as $cm_key=>$commentInfo){
										$responseofOne=array(
											'response' => $commentInfo['twittes_id'],
											'text' => $commentInfo['reply_text'],
											'content_id' =>$conact[2],
											'content_reponse' => $responseObj,
											'project_id' => $project,
											'network_id' => $network,
											'channel_id' =>$conact[0],
											'user_id' => $userid,
											'commented_by_name' => $commentInfo['twittes_screen_name'],
											'commented_by_id' => $commentInfo['twittes_screen_id'],
											//'comment_time' => (string)$commentInfo['reply_time'],
											'likes_on_comment' => $commentInfo['reply_favorite_count']
										);
										$TWinsert=ResponseComments::saveResponses($responseofOne);
										//echo"<pre>";var_dump($zzz);exit;
									}
								}
								if ($reply->httpstatus == 200) {
									$t_like+=$reply->favorite_count;
									$t_share+=$reply->retweet_count;
									$t_comment+=count($tweetreply);
									$tt_like+=$reply->favorite_count;
									$tt_share+=$reply->retweet_count;
									$tt_comment+=count($tweetreply);
									$t_network=2;
									$comments=array();
									if(count($tweetreply)>0){
									$comments[]=array(
										'coments' =>$tweetreply[0]['reply_text'],
										'like_count'=>$reply->favorite_count,
										'tweets_screen_name' => $tweetreply[0]['twittes_screen_name']
									);}
									else{
									$comments[]=array(
										'coments' =>'There is no comment right now.',
										'like_count'=>$reply->favorite_count,
										'tweets_screen_name' => 'NO one'
									);}

								}
								else{
									$comments=array();
									$comments[]=array(
										'coments' =>'There is no comment right Now!',
										'like_count'=>'0',
										'tweets_screen_name' => 'No one '
									);
								}

							}

						}
					}
					elseif($network==3){
						//var_dump('sumit3');
						$responseObj = $NetworkIds[0]['project_channel_ids'][$pkey]['response'][(int)$key];
						$token=$NetworkIds[0]['project_network_ids'][$pkey]['auth_detail'][(int)$key];
						$urllinked="https://api.linkedin.com/v1/companies/2414183/updates/key=".$responseObj."?oauth2_access_token=";
						$accessToken=(json_decode($token)->access_token);
						$url_with_token = $urllinked.$accessToken."&format=json";
						$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."linkedinjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."linkedinjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
							$context = @file_get_contents($url_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
						$context = @file_get_contents($url_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
						if($context!=FALSE){
							$linkedinresponse = json_decode(file_get_contents($url_with_token), true);
							$l_like+=$linkedinresponse["numLikes"];
							$l_comment+=$linkedinresponse["updateComments"]["_total"];
							$tl_like+=$linkedinresponse["numLikes"];
							$tl_comment+=$linkedinresponse["updateComments"]["_total"];
							$l_network=3;
							//echo"<pre>";
							//var_dump($linkedinresponse["updateComments"]["values"]);exit;
							//var_dump($linkedinresponse["likes"]["_total"]);
							$conact=explode('*', $NetworkIds[0]['project_channel_ids'][$pkey][$project][(int)$key]);
							if(count($linkedinresponse["updateComments"]["values"])>0){
								foreach($linkedinresponse["updateComments"]["values"] as $cm_key=>$commentInfo){
									$responseofOne=array(
												'response' => $commentInfo['id'],
												'text' => $commentInfo['comment'],
												'content_id' =>$conact[2],
												'content_reponse' => $responseObj,
												'project_id' => $project,
												'network_id' => $network,
												'channel_id' =>$conact[0],
												'user_id' => $userid,
												'commented_by_name' => $commentInfo['person']['firstName'].' '.$commentInfo['person']['lastName'],
												'commented_by_id' => $commentInfo['id'],
												//'comment_time' => (string)$commentInfo['reply_time'],
												'likes_on_comment' => 0
											);
											$LIinsert=ResponseComments::saveResponses($responseofOne);
								}
							}	
						}
						//var_dump("Linkin:".$l_like.' | '.$l_share);
						
					}
					elseif($network==4){
						//var_dump('sumit4');
					}
					elseif($network==5){
						//var_dump('sumit5');
						$responseObj = $NetworkIds[0]['project_channel_ids'][$pkey]['response'][(int)$key];
						$gooleURL = "https://www.googleapis.com/youtube/v3/videos?id=".$responseObj."&key=AIzaSyDwlUHX89WFsnePZN8UZ1ZmDOXFKvOLNyA&fields=items(id,snippet(channelId,title,categoryId),statistics)&part=snippet,statistics";
					
					$context = '';
					$mod_date= filemtime($this->filepath.$responseObj."googlejson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$responseObj."googlejson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
							$context = @file_get_contents($gooleURL);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
						$context = @file_get_contents($gooleURL);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
						if($context!=FALSE){
							$abc=json_decode($context,true);
							if(count($abc['items'])>0){
								$y_like+=$abc['items'][0]['statistics']['likeCount'];
								$y_comment+=$abc['items'][0]['statistics']['commentCount'];
								$ty_like+=$abc['items'][0]['statistics']['likeCount'];
								$ty_comment+=$abc['items'][0]['statistics']['commentCount'];
								$y_network=5;
							}
						}
					}
					elseif($network==6){
						//var_dump('sumit6');
					}
					elseif($network==7){
						//var_dump('sumit7');
					}
					elseif($network==8){
						//var_dump('sumit8');
					}
					elseif($network==9){
						//var_dump('sumit9');
					}
					$Response_networkwise = array(
						'Facebook'=>$fb=array(
									'like'=>$f_like,
									'share'=>$f_share,
									'comment'=>$f_comment,
									'status'=>$f_network
									),
						'Twitter'=>$twi=array(
									'like'=>$t_like,
									'share'=>$t_share,
									'comment'=>$t_comment,
									'status'=>$t_network
									),
						'Linkedin'=>$li=array(
									'like'=>$l_like,
									'share'=>$l_share,
									'comment'=>$l_comment,
									'status'=>$l_network
									),
						'Youtube'=>$yo=array(
									'like'=>$y_like,
									'share'=>$y_share,
									'comment'=>$y_comment,
									'status'=>$y_network
									)	
						);
				}
				$Response_details[$project] = $Response_networkwise;
				$Response_networkwise = array();
			}
		}
		$fb_pro = array();
		$tw_pro = array();
		$li_pro = array();
		$yu_pro = array();
		foreach($Response_details as $rd_key=>$Response_detail){
			foreach($Response_detail as $rn_key=>$Response_networks){
				if($Response_networks['status']>0){
					//var_dump($Response_networks['status']);
					if($rn_key=='Facebook')
					{
						$fb_pro[]= $rd_key;
						//var_dump("Facebook: ".$rd_key);
					}
					elseif($rn_key=='Twitter')
					{
						$tw_pro[]= $rd_key;
						//var_dump("Twitter: ".$rd_key);
					}
					elseif($rn_key=='Linkedin')
					{
						$li_pro[]= $rd_key;
						//var_dump("Linkedin: ".$rd_key);
					}
					elseif($rn_key=='Youtube')
					{
						$yu_pro[]= $rd_key;
						//var_dump("Youtube: ".$rd_key);
					}
				}
			}
		}
		$Network_responses = array(
			'Facebook'=>$fb=array(
						'id'=>1,
						'like'=>$tf_like,
						'share'=>$tf_share,
						'comment'=>$tf_comment,
						'projects'=>$fb_pro
						),
			'Twitter'=>$twi=array(
						'id'=>2,
						'like'=>$tt_like,
						'share'=>$tt_share,
						'comment'=>$tt_comment,
						'projects'=>$tw_pro
						),
			'Linkedin'=>$li=array(
						'id'=>3,
						'like'=>$tl_like,
						'share'=>$tl_share,
						'comment'=>$tl_comment,
						'projects'=>$li_pro
						),
			'Youtube'=>$yo=array(
						'id'=>5,
						'like'=>$ty_like,
						'share'=>$ty_share,
						'comment'=>$ty_comment,
						'projects'=>$yu_pro
						)
		);
		
		//echo"<pre>";
		//var_dump($NetworkIds);exit;
    	return View::make('response.response')->with('name',$name)->with('id',$this->user->id)->with('Network_responses',$Network_responses);

    }
	
	public function getPostsResponse(){
		// url location
        	$Chklolcation =  new checkLocations();
		$Chklolcation->saveinfo();
		$Contents_Texts = array();
		$pro_id=(int)Route::Input('pro_id');
		$net_name=(int)Route::Input('net_name');
		$NetworkIds = Publishstatus::getNetworkIds($this->user->id);
		foreach($NetworkIds[0]["project_channel_ids"] as $pro_key=>$projects)
		{
			if(key($projects)==$pro_id)
			{
				foreach($projects[key($projects)] as $cot_key=>$content){
					if( $net_name==$NetworkIds[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]){
						$contentId_response=explode("*", $content);
						$contentText = Content::getContentById($contentId_response[2]);
						$Contents_Texts[] = $contentText->content;
					}
				}
			}
		}
		
		//echo"<pre>";
		//var_dump($Contents_Texts);exit;
		return View::make('response.postsResponse')->with('name','Hello!')->with('id',$this->user->id)->with('NetworkIds',$NetworkIds)->with('Contents_Texts',$Contents_Texts);
	}
	
	public function getResponseOnEveryPost(){
		$userid=$this->user->id;
		$Contents_Texts = array();
		$Response_allposts = array();
		$NetworkIds = Publishstatus::getNetworkIds($userid);
		foreach($NetworkIds[0]["project_channel_ids"] as $pro_key=>$projects)
		{
			foreach($projects[key($projects)] as $cot_key=>$content){
					//if( $net_name==$NetworkIds[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]){
				$contentId_response=explode("*", $content);
				$contentText = Content::getContentById($contentId_response[2]);
				$Contents_Texts[] = array(
					'text'=>$contentText->content,
					'network_id'=>(int)$NetworkIds[0]["project_network_ids"][$pro_key][key($projects)][$cot_key],
					'project_id'=>key($projects),
					'response'=>$contentId_response[1],
					'auth'=>$NetworkIds[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key],
					'conact'=>$NetworkIds[0]['project_channel_ids'][$pro_key][key($projects)][$cot_key]
				);
					//}
			}
	
		}
		foreach($Contents_Texts as $ct_key=>$Contents_Text){
			$f_like=0;$t_like=0;$l_like=0;$y_like=0;
			$fs_like='No';$ts_like='No';$ls_like='No';$ys_like='No';
			$f_share=0;$t_share=0;$l_share=0;$y_share=0;
			$fs_share='No';$ts_share='No';$ls_share='No';$ys_share='No';
			$f_comment=0;$t_comment=0;$l_comment=0;$y_comment=0;
			$fs_comment='No';$ts_comment='No';$ls_comment='No';$ys_comment='No';
			$f_network=0;$t_network=0;$l_network=0;$y_network=0;
			if($Contents_Text['network_id']==1){
				$responseObj = $Contents_Text['response'];
				$token=$Contents_Text['auth'];
				//Here get the likes
				$likesurl="https://graph.facebook.com/likes?id=".$responseObj."&access_token=";
				$accessToken=(json_decode($token)->access_token);
				$likesurl_with_token = $likesurl.$accessToken ;
				//$context = @file_get_contents($likesurl_with_token);
				
				$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."likewithjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."likewithjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
							$context = @file_get_contents($likesurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
						$context = @file_get_contents($likesurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
				
				
				if($context!=FALSE){
					$jsonlikes = file_get_contents($likesurl_with_token);
					$likesResponses=json_decode($jsonlikes, true);
					$userLikes = "";
					$likescount=2;
					$totalcount=0;
					if($likesResponses['data']!=null && count($likesResponses)>=2){
						$totalcount=count($likesResponses['data']);
					}
					$f_like+=$totalcount;
					$fs_like=($f_like>0)?'Yes':'No';
					$f_network=1;
				}
				//shared post
				$data=explode('_', $responseObj);
				$p_id=(count($data)>1)?$data[1]:$data[0];
				$sharedurl="https://graph.facebook.com/".$p_id."/sharedposts?access_token=";
				$sharedurlurl_with_token = $sharedurl.$accessToken ;
$context = '';
					$mod_date= filemtime($accessToken."sharedpostsfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($accessToken."sharedpostsfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
							$context = @file_get_contents($sharedurlurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
						$context = @file_get_contents($sharedurlurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
				
							
				
				
				if($context!=FALSE){
					$jsonshares = file_get_contents($sharedurlurl_with_token);
					$sharesResponses=json_decode($jsonshares, true);
					$f_share+=count($sharesResponses['data']);
					$fs_share=($f_share>0)?'Yes':'No';
					$f_network=1;
					$sharedhistory = array();
					if($sharesResponses['data']!=null && count($sharesResponses)>=2){
						foreach($sharesResponses['data'] as $sharesResponse){
							$sharedhistory[]= array(
								'story' => $sharesResponse['story'], 
							);
						}
					}
					else{
					 $sharedhistory[]= array(
							'story' =>'no any share your post.', 
						);
					}
				}
				//Here get the comments
				$commenturl="https://graph.facebook.com/".$responseObj."/comments?summary=true&access_token=";
					$commenturl_with_token = $commenturl.$accessToken ;

					
					$json = '';
					$mod_date= filemtime($accessToken."commentsfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($accessToken."commentsfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($accessToken."commentsfacebook.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
							fwrite($Userfile, $json);
							fclose($Userfile);
						} else {
								$Userfile = fopen($accessToken."commentsfacebook.txt", "r");
								$json = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($accessToken."commentsfacebook.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
						fwrite($Userfile, $json);
						fclose($Userfile);
					}
				if($json!=FALSE){
					$commentResponses=json_decode($json, true);
					if(count($commentResponses)>1){
						//Save it DB
						$conact=explode('*', $Contents_Text['conact']);
						if(count($commentResponses)>0){
							if(count($commentResponses['data'])>0){
								foreach($commentResponses['data'] as $cm_key=>$commentInfo){
									$responseofOne=array(
										'response' => $commentInfo['id'],
										'text' => $commentInfo['message'],
										'content_id' =>$conact[2],
										'content_reponse' => $responseObj,
										'project_id' => $Contents_Text['project_id'],
										'network_id' => $Contents_Text['network_id'],
										'channel_id' =>$conact[0],
										'user_id' => $userid,
										'commented_by_name' => $commentInfo['from']['name'],
										'commented_by_id' => $commentInfo['from']['id'],
										'comment_time' => $commentInfo['created_time'],
										'likes_on_comment' => $commentInfo['like_count']
									);
									$FBinsert=ResponseComments::saveResponses($responseofOne);
								}
							}
						}
						//
						$f_comment+=$commentResponses['summary']['total_count'];
						$fs_comment=($f_comment>0)?'Yes':'No';
					}
					$f_network=1;
				}
				$Response_allposts[] = array(
							'text'=>$Contents_Text['text'],
							'network_name'=>'Facebook',
							'project_id'=>$Contents_Text['project_id'],
							'like_status'=>$fs_like,
							'share_status'=>$fs_share,
							'comment_status'=>$fs_comment,
							'like'=>$f_like,
							'share'=>$f_share,
							'comment'=>$f_comment
						);
			}
			elseif($Contents_Text['network_id']==2){
				$networkInfo = DB::table('network')->where('id', $Contents_Text['network_id'])->first();
				$CONSUMER_KEYs = $networkInfo->api_key;
				$CONSUMER_SECRETs = $networkInfo->secret_key;
				$token=$Contents_Text['auth'];
				if ($token != null) {
					$responseObj = $Contents_Text['response'];
					if ($responseObj != null) {
						$accessToken = (json_decode($token)->access_token);
						$accesssecret = (json_decode($token)->access_secret);
						\Codebird\Codebird::setConsumerKey($CONSUMER_KEYs, $CONSUMER_SECRETs);
						$cb = \Codebird\Codebird::getInstance();
						$cb->setToken($accessToken, $accesssecret);
						$reply = $cb->statuses_show_ID('id=' . $responseObj);
						$rereply = (array)$cb->statuses_mentionsTimeline();
						$tweetreply = array();
						foreach ($rereply as $rep) {
							if (count((array)$rep) > 3 && $responseObj == $rep->in_reply_to_status_id_str) {
								$tweetreply[] = array(
									'reply_text' => $rep->text,
									'twittes_id' => $rep->id_str,
									'twittes_screen_name' => $rep->user->name,
									'commented_by' => $rep->user->id,
									
								);
								$ts_comment='Yes';
							}
						}
						//Save in DB
						$conact=explode('*', $Contents_Text['conact']);
						if(count($tweetreply)>0){
							foreach($tweetreply as $cm_key=>$commentInfo){
								$responseofOne=array(
									'response' => $commentInfo['twittes_id'],
									'text' => $commentInfo['reply_text'],
									'commented_by_name' => $commentInfo['twittes_screen_name'],
									'commented_by_id' => $commentInfo['commented_by'],
									'content_id' =>$conact[2],
									'content_reponse' => $responseObj,
									'project_id' => $Contents_Text['project_id'],
									'network_id' => $Contents_Text['network_id'],
									'channel_id' =>$conact[0],
									'user_id' => $userid
								);
								$TWinsert=ResponseComments::saveResponses($responseofOne);
							}
						}
						//
						if ($reply->httpstatus == 200) {
							$t_like+=$reply->favorite_count;
							$ts_like=($t_like>0)?'Yes':'No';
							$t_share+=$reply->retweet_count;
							$ts_share=($t_share>0)?'Yes':'No';
							$t_comment+=count($tweetreply);
							$t_network=2;
						}
					}
				}
				$Response_allposts[] = array(
							'text'=>$Contents_Text['text'],
							'network_name'=>'Twitter',
							'project_id'=>$Contents_Text['project_id'],
							'like_status'=>$ts_like,
							'share_status'=>$ts_share,
							'comment_status'=>$ts_comment,
							'like'=>$t_like,
							'share'=>$t_share,
							'comment'=>$t_comment
						);
			}
			elseif($Contents_Text['network_id']==3){
				$responseObj = $Contents_Text['response'];
				$token=$Contents_Text['auth'];
				$urllinked="https://api.linkedin.com/v1/companies/2414183/updates/key=".$responseObj."?oauth2_access_token=";
						$accessToken=(json_decode($token)->access_token);
						$url_with_token = $urllinked.$accessToken."&format=json";
						$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."linkedinjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."linkedinjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
							$context = @file_get_contents($url_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
						$context = @file_get_contents($url_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
				if($context!=FALSE){
					//Save in DB
					$linkedinresponse = json_decode(file_get_contents($url_with_token), true);
					$conact=explode('*', $Contents_Text['conact']);
					if((int)$linkedinresponse["updateComments"]["_total"]>0){
						foreach($linkedinresponse["updateComments"]["values"] as $cm_key=>$commentInfo){
							$responseofOne=array(
										'response' => $commentInfo['id'],
										'text' => $commentInfo['comment'],
										'content_id' =>$conact[2],
										'content_reponse' => $responseObj,
										'project_id' => $Contents_Text['project_id'],
										'network_id' => $Contents_Text['network_id'],
										'channel_id' =>$conact[0],
										'user_id' => $userid,
										'commented_by_name' => $commentInfo['person']['firstName'].' '. $commentInfo['person']['lastName'],
										'commented_by_id' =>$commentInfo['person']['id'],
										//'comment_time' => $commentInfo['created_time'],
										'likes_on_comment' => 0
									);
									$LIinsert=ResponseComments::saveResponses($responseofOne);
						}
					}
					//
					$l_like+=$linkedinresponse["numLikes"];
					$ls_like=($l_like>0)?'Yes':'No';
					$l_comment+=$linkedinresponse["updateComments"]["_total"];
					$ls_comment=($l_comment>0)?'Yes':'No';
					$l_network=3;
				}
				$Response_allposts[] = array(
							'text'=>$Contents_Text['text'],
							'network_name'=>'Linkedin',
							'project_id'=>$Contents_Text['project_id'],
							'like_status'=>$ls_like,
							'share_status'=>$ls_share,
							'comment_status'=>$ls_comment,
							'like'=>$l_like,
							'share'=>$l_share,
							'comment'=>$l_comment
					);
			}
			elseif($Contents_Text['network_id']==4){
				//var_dump('sumit4');
			}
			elseif($Contents_Text['network_id']==5){
				$responseObj = $Contents_Text['response'];
				$gooleURL = "https://www.googleapis.com/youtube/v3/videos?id=".$responseObj."&key=AIzaSyDwlUHX89WFsnePZN8UZ1ZmDOXFKvOLNyA&fields=items(id,snippet(channelId,title,categoryId),statistics)&part=snippet,statistics";
					
					$context = '';
					$mod_date= filemtime($this->filepath.$responseObj."googlejson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$responseObj."googlejson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
							$context = @file_get_contents($gooleURL);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
						$context = @file_get_contents($gooleURL);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
				if($context!=FALSE){
					$abc=json_decode($context,true);
					if(count($abc['items'])>0){
						$y_like+=$abc['items'][0]['statistics']['likeCount'];
						$ys_like=($y_like>0)?'Yes':'No';
						$y_comment+=$abc['items'][0]['statistics']['commentCount'];
						$ys_comment=($y_comment>0)?'Yes':'No';
						$y_network=5;
					}
				}
				$Response_allposts[] = array(
							'text'=>$Contents_Text['text'],
							'network_name'=>'Youtube',
							'project_id'=>$Contents_Text['project_id'],
							'like_status'=>$ys_like,
							'share_status'=>$ys_share,
							'comment_status'=>$ys_comment,
							'like'=>$y_like,
							'share'=>$y_share,
							'comment'=>$y_comment
					);
			}
			elseif($Contents_Text['network_id']==6){
				//var_dump('sumit6');
			}
			elseif($Contents_Text['network_id']==7){
				//var_dump('sumit7');
			}
			elseif($Contents_Text['network_id']==8){
				//var_dump('sumit8');
			}
			elseif($Contents_Text['network_id']==9){
				//var_dump('sumit9');
			}
		}
		return View::make('response.responseAllposts')->with('name','Hello!')->with('id',$this->user->id)->with('Response_allposts',$Response_allposts);
	}
	
	public function getCommentsOfPost(){
		$allComments=ResponseComments::getComments($this->user->id);
		//echo"<pre>";
		//var_dump($allComments);exit;
		return View::make('response.responseAllcomments')->with('name','Hello!')->with('id',$this->user->id)->with('allComments',$allComments);
	}
	
	public function getTopResponse(){
		$Contents_Texts = array();
		$Response_details = array();
		$Response_networkwise = array();
		$manageProject=ProjectUsers::getmanageProject($this->user->id);
		$ownProject=Project::getAllProjects($this->user->id, 24);
		$projects = array(
			'manageProject' => $manageProject,
			'ownProject' => $ownProject
		);
		$tf_like=0;$tt_like=0;$tl_like=0;$ty_like=0;
		$tf_share=0;$tt_share=0;$tl_share=0;$ty_share=0;
		$tf_comment=0;$tt_comment=0;$tl_comment=0;$ty_comment=0;
		$tf_project=array();$tt_project=array();$tl_project=array();$ty_project=array();
		$tf_channel_id=array();$tt_channel_id=array();$tl_channel_id=array();$ty_channel_id=array();
		$datas=Publishstatus::getResponseIds($projects);
		foreach($datas[0]["project_channel_ids"] as $pro_key=>$projects)
		{
			foreach($projects[key($projects)] as $cot_key=>$content){
					//if( $net_name==$NetworkIds[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]){
				$contentId_response=explode("*", $content);
				$contentText = Content::getContentById($contentId_response[2]);
				$Contents_Texts[] = array(
					'text'=>$contentText->content,
					'network_id'=>(int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key],
					'project_id'=>key($projects),
					'response'=>$contentId_response[1],
					'auth'=>$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key],
					'conact'=>$datas[0]['project_channel_ids'][$pro_key][key($projects)][$cot_key]
				);
					//}
			}
		}
		foreach($Contents_Texts as $ct_key=>$Contents_Text){
			$f_like=0;$t_like=0;$l_like=0;$y_like=0;
			$f_share=0;$t_share=0;$l_share=0;$y_share=0;
			$f_comment=0;$t_comment=0;$l_comment=0;$y_comment=0;
			$f_network=0;$t_network=0;$l_network=0;$y_network=0;
			if($Contents_Text['network_id']==1){
				$responseObj = $Contents_Text['response'];
				$token=$Contents_Text['auth'];
				//Here get the likes
				$likesurl="https://graph.facebook.com/likes?id=".$responseObj."&access_token=";
				$accessToken=(json_decode($token)->access_token);
				$likesurl_with_token = $likesurl.$accessToken ;
				//$context = @file_get_contents($likesurl_with_token);
				
				$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."likewithjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."likewithjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
							$context = @file_get_contents($likesurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
						$context = @file_get_contents($likesurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
				
				if($context!=FALSE){
					$jsonlikes = file_get_contents($likesurl_with_token);
					$likesResponses=json_decode($jsonlikes, true);
					$userLikes = "";
					$likescount=2;
					$totalcount=0;
					if($likesResponses['data']!=null && count($likesResponses)>=2){
						$totalcount=count($likesResponses['data']);
					}
					$f_like+=$totalcount;
					$tf_like+=$totalcount;
					$f_network=1;
				}
				//shared post
				$data=explode('_', $responseObj);
				$p_id=(count($data)>1)?$data[1]:$data[0];
				$sharedurl="https://graph.facebook.com/".$p_id."/sharedposts?access_token=";
				$sharedurlurl_with_token = $sharedurl.$accessToken ;
$context = '';
					$mod_date= filemtime($accessToken."sharedpostsfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($accessToken."sharedpostsfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
							$context = @file_get_contents($sharedurlurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
						$context = @file_get_contents($sharedurlurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
				if($context!=FALSE){
					$jsonshares = file_get_contents($sharedurlurl_with_token);
					$sharesResponses=json_decode($jsonshares, true);
					$f_share+=count($sharesResponses['data']);
					$tf_share+=count($sharesResponses['data']);
					$f_network=1;
					$sharedhistory = array();
					if($sharesResponses['data']!=null && count($sharesResponses)>=2){
						foreach($sharesResponses['data'] as $sharesResponse){
							$sharedhistory[]= array(
								'story' => $sharesResponse['story'], 
							);
						}
					}
					else{
					 $sharedhistory[]= array(
							'story' =>'no any share your post.', 
						);
					}
				}
				//Here get the comments
				$commenturl="https://graph.facebook.com/".$responseObj."/comments?summary=true&access_token=";
					$commenturl_with_token = $commenturl.$accessToken ;

					
					$json = '';
					$mod_date= filemtime($accessToken."commentsfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($accessToken."commentsfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($accessToken."commentsfacebook.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
							fwrite($Userfile, $json);
							fclose($Userfile);
						} else {
								$Userfile = fopen($accessToken."commentsfacebook.txt", "r");
								$json = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($accessToken."commentsfacebook.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
						fwrite($Userfile, $json);
						fclose($Userfile);
					}
				if($json!=FALSE){
					$commentResponses=json_decode($json, true);
					if(count($commentResponses)>1){
						$f_comment+=$commentResponses['summary']['total_count'];
						$tf_comment+=$commentResponses['summary']['total_count'];
					}
					$f_network=1;
				}
				$tf_project[]=$Contents_Text['project_id'];
				$concats=explode("*",$Contents_Text['conact']);
				$tf_channel_id[]=$concats[0];
			}
			elseif($Contents_Text['network_id']==2){
				$networkInfo = DB::table('network')->where('id', $Contents_Text['network_id'])->first();
				$CONSUMER_KEYs = $networkInfo->api_key;
				$CONSUMER_SECRETs = $networkInfo->secret_key;
				$token=$Contents_Text['auth'];
				if ($token != null) {
					$responseObj = $Contents_Text['response'];
					if ($responseObj != null) {
						$accessToken = (json_decode($token)->access_token);
						$accesssecret = (json_decode($token)->access_secret);
						\Codebird\Codebird::setConsumerKey($CONSUMER_KEYs, $CONSUMER_SECRETs);
						$cb = \Codebird\Codebird::getInstance();
						$cb->setToken($accessToken, $accesssecret);
						$reply = $cb->statuses_show_ID('id=' . $responseObj);
						$rereply = (array)$cb->statuses_mentionsTimeline();
						$tweetreply = array();
						foreach ($rereply as $rep) {
							if (count((array)$rep) > 3 && $responseObj == $rep->in_reply_to_status_id_str) {
								$tweetreply[] = array(
									'reply_text' => $rep->text,
									'twittes_id' => $rep->id_str,
									'twittes_screen_name' => $rep->user->name,
								);
								$ts_comment='Yes';
							}
						}
						if ($reply->httpstatus == 200) {
							$t_like+=$reply->favorite_count;
							$t_share+=$reply->retweet_count;
							$t_comment+=count($tweetreply);
							$tt_like+=$reply->favorite_count;
							$tt_share+=$reply->retweet_count;
							$tt_comment+=count($tweetreply);
							$t_network=2;
						}
					}
				}
				$tt_project[]=$Contents_Text['project_id'];
				$concats=explode("*",$Contents_Text['conact']);
				$tt_channel_id[]=$concats[0];
			}
			elseif($Contents_Text['network_id']==3){
				$responseObj = $Contents_Text['response'];
				$token=$Contents_Text['auth'];
				$urllinked="https://api.linkedin.com/v1/companies/2414183/updates/key=".$responseObj."?oauth2_access_token=";
						$accessToken=(json_decode($token)->access_token);
						$url_with_token = $urllinked.$accessToken."&format=json";
						$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."linkedinjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."linkedinjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
							$context = @file_get_contents($url_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
						$context = @file_get_contents($url_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
				if($context!=FALSE){
					$linkedinresponse = json_decode(file_get_contents($url_with_token), true);
					$l_like+=$linkedinresponse["numLikes"];
					$l_comment+=$linkedinresponse["updateComments"]["_total"];
					$tl_like+=$linkedinresponse["numLikes"];
					$tl_comment+=$linkedinresponse["updateComments"]["_total"];
					$l_network=3;
				}
				$tl_project[]=$Contents_Text['project_id'];
				$concats=explode("*",$Contents_Text['conact']);
				$tl_channel_id[]=$concats[0];
			}
			elseif($Contents_Text['network_id']==4){
				//var_dump('sumit4');
			}
			elseif($Contents_Text['network_id']==5){
				$responseObj = $Contents_Text['response'];
				$gooleURL = "https://www.googleapis.com/youtube/v3/videos?id=".$responseObj."&key=AIzaSyDwlUHX89WFsnePZN8UZ1ZmDOXFKvOLNyA&fields=items(id,snippet(channelId,title,categoryId),statistics)&part=snippet,statistics";
					
					$context = '';
					$mod_date= filemtime($this->filepath.$responseObj."googlejson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$responseObj."googlejson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
							$context = @file_get_contents($gooleURL);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
						$context = @file_get_contents($gooleURL);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
				if($context!=FALSE){
					$abc=json_decode($context,true);
					if(count($abc['items'])>0){
						$y_like+=$abc['items'][0]['statistics']['likeCount'];
						$y_comment+=$abc['items'][0]['statistics']['commentCount'];
						$ty_like+=$abc['items'][0]['statistics']['likeCount'];
						$ty_comment+=$abc['items'][0]['statistics']['commentCount'];
						$y_network=5;
					}
				}
				$ty_project[]=$Contents_Text['project_id'];
				$concats=explode("*",$Contents_Text['conact']);
				$ty_channel_id[]=$concats[0];
			}
			elseif($Contents_Text['network_id']==6){
				//var_dump('sumit6');
			}
			elseif($Contents_Text['network_id']==7){
				//var_dump('sumit7');
			}
			elseif($Contents_Text['network_id']==8){
				//var_dump('sumit8');
			}
			elseif($Contents_Text['network_id']==9){
				//var_dump('sumit9');
			}
			
		}
		$Network_responses = array(
			'Facebook'=>$fb=array(
						'id'=>1,
						'like'=>$tf_like,
						'share'=>$tf_share,
						'comment'=>$tf_comment,
						'projects'=>array_unique($tf_project),
						'channel_id'=>array_unique($tf_channel_id)
						),
			'Twitter'=>$twi=array(
						'id'=>2,
						'like'=>$tt_like,
						'share'=>$tt_share,
						'comment'=>$tt_comment,
						'projects'=>array_unique($tt_project),
						'channel_id'=>array_unique($tt_channel_id)
						),
			'Linkedin'=>$li=array(
						'id'=>3,
						'like'=>$tl_like,
						'share'=>$tl_share,
						'comment'=>$tl_comment,
						'projects'=>array_unique($tl_project),
						'channel_id'=>array_unique($tl_channel_id)
						),
			'Youtube'=>$yo=array(
						'id'=>5,
						'like'=>$ty_like,
						'share'=>$ty_share,
						'comment'=>$ty_comment,
						'projects'=>array_unique($ty_project),
						'channel_id'=>array_unique($ty_channel_id)
						)
		);
		//echo"<pre>";
		//var_dump($Network_responses);exit;
		return View::make('response.topResponse')->with('name','Hello!')->with('id',$this->user->id)->with('Network_responses',$Network_responses);
	}
		
	public function getProjectResponse(){
		// url location
        $Chklolcation =  new checkLocations();
		$Chklolcation->saveinfo();
		$Contents_Texts = array();
		$Project_responses = array();
		$manageProject=ProjectUsers::getmanageProject($this->user->id);
		$ownProject=Project::getAllProjects($this->user->id, 24);
		$projects = array(
			'manageProject' => $manageProject,
			'ownProject' => $ownProject
		);
		$datas=Publishstatus::getResponseIds($projects);
		echo"<pre>";
		var_dump($datas);exit;
		foreach($datas[0]["project_channel_ids"] as $pro_key=>$projects)
		{
			$f_like=0;$t_like=0;$l_like=0;$y_like=0;
			$f_share=0;$t_share=0;$l_share=0;$y_share=0;
			$f_comment=0;$t_comment=0;$l_comment=0;$y_comment=0;
			$f_network=0;$t_network=0;$l_network=0;$y_network=0;
			foreach($projects[key($projects)] as $cot_key=>$content){
				$contentId_response=explode("*", $content);
				
				if((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==1){
				$responseObj = $contentId_response[1];
				$token=$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key];
				//Here get the likes
				$likesurl="https://graph.facebook.com/likes?id=".$responseObj."&access_token=";
				$accessToken=(json_decode($token)->access_token);
				$likesurl_with_token = $likesurl.$accessToken ;
				//$context = @file_get_contents($likesurl_with_token);
				
				
				$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."likewithjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."likewithjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
							$context = @file_get_contents($likesurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
						$context = @file_get_contents($likesurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
				
				if($context!=FALSE){
					$jsonlikes = file_get_contents($likesurl_with_token);
					$likesResponses=json_decode($jsonlikes, true);
					$userLikes = "";
					$likescount=2;
					$totalcount=0;
					if($likesResponses['data']!=null && count($likesResponses)>=2){
						$totalcount=count($likesResponses['data']);
					}
					$f_like+=$totalcount;
					$f_network=1;
				}
				//shared post
				$data=explode('_', $responseObj);
				$p_id=(count($data)>1)?$data[1]:$data[0];
				$sharedurl="https://graph.facebook.com/".$p_id."/sharedposts?access_token=";
				$sharedurlurl_with_token = $sharedurl.$accessToken ;
$context = '';
					$mod_date= filemtime($accessToken."sharedpostsfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($accessToken."sharedpostsfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
							$context = @file_get_contents($sharedurlurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
						$context = @file_get_contents($sharedurlurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
				if($context!=FALSE){
					$jsonshares = file_get_contents($sharedurlurl_with_token);
					$sharesResponses=json_decode($jsonshares, true);
					$f_share+=count($sharesResponses['data']);
					$f_network=1;
					$sharedhistory = array();
					if($sharesResponses['data']!=null && count($sharesResponses)>=2){
						foreach($sharesResponses['data'] as $sharesResponse){
							$sharedhistory[]= array(
								'story' => $sharesResponse['story'], 
							);
						}
					}
					else{
					 $sharedhistory[]= array(
							'story' =>'no any share your post.', 
						);
					}
				}
				//Here get the comments
				$commenturl="https://graph.facebook.com/".$responseObj."/comments?summary=true&access_token=";
					$commenturl_with_token = $commenturl.$accessToken ;

					
					$json = '';
					$mod_date= filemtime($accessToken."commentsfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($accessToken."commentsfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($accessToken."commentsfacebook.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
							fwrite($Userfile, $json);
							fclose($Userfile);
						} else {
								$Userfile = fopen($accessToken."commentsfacebook.txt", "r");
								$json = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($accessToken."commentsfacebook.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
						fwrite($Userfile, $json);
						fclose($Userfile);
					}
				if($json!=FALSE){
					$commentResponses=json_decode($json, true);
					if(count($commentResponses)>1){
						$f_comment+=$commentResponses['summary']['total_count'];
					}
					$f_network=1;
				}
			}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==2){
					$networkInfo = DB::table('network')->where('id', 2)->first();
					$CONSUMER_KEYs = $networkInfo->api_key;
					$CONSUMER_SECRETs = $networkInfo->secret_key;
					$token=$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key];
					if ($token != null) {
						$responseObj = $contentId_response[1];
						if ($responseObj != null) {
							$accessToken = (json_decode($token)->access_token);
							$accesssecret = (json_decode($token)->access_secret);
							\Codebird\Codebird::setConsumerKey($CONSUMER_KEYs, $CONSUMER_SECRETs);
							$cb = \Codebird\Codebird::getInstance();
							$cb->setToken($accessToken, $accesssecret);
							$reply = $cb->statuses_show_ID('id=' . $responseObj);
							$rereply = (array)$cb->statuses_mentionsTimeline();
							$tweetreply = array();
							foreach ($rereply as $rep) {
								if (count((array)$rep) > 3 && $responseObj == $rep->in_reply_to_status_id_str) {
									$tweetreply[] = array(
										'reply_text' => $rep->text,
										'twittes_id' => $rep->id_str,
										'twittes_screen_name' => $rep->user->name,
									);
									$ts_comment='Yes';
								}
							}
							if ($reply->httpstatus == 200) {
								$t_like+=$reply->favorite_count;
								$t_share+=$reply->retweet_count;
								$t_comment+=count($tweetreply);
								$t_network=2;
							}
						}
					}
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==3){
					$responseObj = $contentId_response[1];
					$token=$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key];
					$urllinked="https://api.linkedin.com/v1/companies/2414183/updates/key=".$responseObj."?oauth2_access_token=";
						$accessToken=(json_decode($token)->access_token);
						$url_with_token = $urllinked.$accessToken."&format=json";
						$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."linkedinjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."linkedinjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
							$context = @file_get_contents($url_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
						$context = @file_get_contents($url_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					if($context!=FALSE){
						$linkedinresponse = json_decode(file_get_contents($url_with_token), true);
						$l_like+=$linkedinresponse["numLikes"];
						$l_comment+=$linkedinresponse["updateComments"]["_total"];
						$l_network=3;
					}
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==4){
					//var_dump('sumit4');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==5){
					$responseObj = $contentId_response[1];
					$gooleURL = "https://www.googleapis.com/youtube/v3/videos?id=".$responseObj."&key=AIzaSyDwlUHX89WFsnePZN8UZ1ZmDOXFKvOLNyA&fields=items(id,snippet(channelId,title,categoryId),statistics)&part=snippet,statistics";
					
					$context = '';
					$mod_date= filemtime($this->filepath.$responseObj."googlejson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$responseObj."googlejson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
							$context = @file_get_contents($gooleURL);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
						$context = @file_get_contents($gooleURL);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					if($context!=FALSE){
						$abc=json_decode($context,true);
						if(count($abc['items'])>0){
							$y_like+=$abc['items'][0]['statistics']['likeCount'];
							$y_comment+=$abc['items'][0]['statistics']['commentCount'];
							$y_network=5;
						}
					}
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==6){
					//var_dump('sumit6');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==7){
					//var_dump('sumit7');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==8){
					//var_dump('sumit8');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==9){
					//var_dump('sumit9');
				}
			}
			$Project_responses[] = array(
				key($projects)=> array(
				'like' => $f_like+$t_like+$l_like+$y_like,
				'share' => $f_share+$t_share+$l_share+$y_share,
				'comment' => $f_comment+$t_comment+$l_comment+$y_comment,
				)
			);
		}
		//echo"<pre>";
		//var_dump($Project_responses);exit;
		return View::make('response.projectResponse')->with('name','Hello!')->with('id',$this->user->id)->with('Project_responses',$Project_responses);
	}
	
	public function getProjectResponseDetails(){
		$pro_id=(int)Route::Input('pro_id');
		$conntent_result=array();
		$total_channelid=array();
		$total_channel_info=array();
		$total_content_text=array();
		$total_content_info=array();
		$manageProject=array();
		$ownProject=array();
		$ownProject[]=$pro_id;
		$projects = array(
			'manageProject' => $manageProject,
			'ownProject' => $ownProject
		);
		$datas=Publishstatus::getResponseIds($projects);
		foreach($datas[0]["project_channel_ids"] as $pro_key=>$projects)
		{
			foreach($projects[key($projects)] as $cot_key=>$content){
			$f_like=0;$t_like=0;$l_like=0;$y_like=0;
			$f_share=0;$t_share=0;$l_share=0;$y_share=0;
			$f_comment=0;$t_comment=0;$l_comment=0;$y_comment=0;
			$f_network=0;$t_network=0;$l_network=0;$y_network=0;
			$f_channel_id=0;$t_channel_id=0;$l_channel_id=0;$y_channel_id=0;
				$contentId_response=explode("*", $content);
				$total_channelid[]=$contentId_response[0];
				if((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==1){
					$f_channel_id=$contentId_response[0];
					$responseObj = $contentId_response[1];
					$token=$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key];
					//Here get the likes
					$likesurl="https://graph.facebook.com/likes?id=".$responseObj."&access_token=";
					$accessToken=(json_decode($token)->access_token);
					$likesurl_with_token = $likesurl.$accessToken ;
					//$context = @file_get_contents($likesurl_with_token);
					
					$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."likewithjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."likewithjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
							$context = @file_get_contents($likesurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
						$context = @file_get_contents($likesurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					
					if($context!=FALSE){
						$jsonlikes = file_get_contents($likesurl_with_token);
						$likesResponses=json_decode($jsonlikes, true);
						$userLikes = "";
						$likescount=2;
						$totalcount=0;
						if($likesResponses['data']!=null && count($likesResponses)>=2){
							$totalcount=count($likesResponses['data']);
						}
						$f_like+=$totalcount;
						$f_network=1;
					}
					//shared post
					$data=explode('_', $responseObj);
					$p_id=(count($data)>1)?$data[1]:$data[0];
					$sharedurl="https://graph.facebook.com/".$p_id."/sharedposts?access_token=";
				$sharedurlurl_with_token = $sharedurl.$accessToken ;
$context = '';
					$mod_date= filemtime($accessToken."sharedpostsfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($accessToken."sharedpostsfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
							$context = @file_get_contents($sharedurlurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
						$context = @file_get_contents($sharedurlurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					if($context!=FALSE){
						$jsonshares = file_get_contents($sharedurlurl_with_token);
						$sharesResponses=json_decode($jsonshares, true);
						$f_share+=count($sharesResponses['data']);
						$f_network=1;
						$sharedhistory = array();
						if($sharesResponses['data']!=null && count($sharesResponses)>=2){
							foreach($sharesResponses['data'] as $sharesResponse){
								$sharedhistory[]= array(
									'story' => $sharesResponse['story'], 
								);
							}
						}
						else{
						 $sharedhistory[]= array(
								'story' =>'no any share your post.', 
							);
						}
					}
					//Here get the comments
					$commenturl="https://graph.facebook.com/".$responseObj."/comments?summary=true&access_token=";
					$commenturl_with_token = $commenturl.$accessToken ;

					
					$json = '';
					$mod_date= filemtime($accessToken."commentsfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($accessToken."commentsfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($accessToken."commentsfacebook.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
							fwrite($Userfile, $json);
							fclose($Userfile);
						} else {
								$Userfile = fopen($accessToken."commentsfacebook.txt", "r");
								$json = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($accessToken."commentsfacebook.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
						fwrite($Userfile, $json);
						fclose($Userfile);
					}
					if($json!=FALSE){
						$commentResponses=json_decode($json, true);
						if(count($commentResponses)>1){
							$f_comment+=$commentResponses['summary']['total_count'];
						}
						$f_network=1;
					}
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$f_like,
					'share'=>$f_share,
					'comment'=>$f_comment,
					'channel'=>$f_channel_id
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==2){
					$t_channel_id=$contentId_response[0];
					$networkInfo = DB::table('network')->where('id', 2)->first();
					$CONSUMER_KEYs = $networkInfo->api_key;
					$CONSUMER_SECRETs = $networkInfo->secret_key;
					$token=$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key];
					if ($token != null) {
						$responseObj = $contentId_response[1];
						if ($responseObj != null) {
							$accessToken = (json_decode($token)->access_token);
							$accesssecret = (json_decode($token)->access_secret);
							\Codebird\Codebird::setConsumerKey($CONSUMER_KEYs, $CONSUMER_SECRETs);
							$cb = \Codebird\Codebird::getInstance();
							$cb->setToken($accessToken, $accesssecret);
							$reply = $cb->statuses_show_ID('id=' . $responseObj);
							$rereply = (array)$cb->statuses_mentionsTimeline();
							$tweetreply = array();
							foreach ($rereply as $rep) {
								if (count((array)$rep) > 3 && $responseObj == $rep->in_reply_to_status_id_str) {
									$tweetreply[] = array(
										'reply_text' => $rep->text,
										'twittes_id' => $rep->id_str,
										'twittes_screen_name' => $rep->user->name,
									);
									$ts_comment='Yes';
								}
							}
							if ($reply->httpstatus == 200) {
								$t_like+=$reply->favorite_count;
								$t_share+=$reply->retweet_count;
								$t_comment+=count($tweetreply);
								$t_network=2;
							}
						}
					}
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$t_like,
					'share'=>$t_share,
					'comment'=>$t_comment,
					'channel'=>$t_channel_id
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==3){
					$l_channel_id=$contentId_response[0];
					$responseObj = $contentId_response[1];
					$token=$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key];
					$urllinked="https://api.linkedin.com/v1/companies/2414183/updates/key=".$responseObj."?oauth2_access_token=";
						$accessToken=(json_decode($token)->access_token);
						$url_with_token = $urllinked.$accessToken."&format=json";
						$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."linkedinjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."linkedinjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
							$context = @file_get_contents($url_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
						$context = @file_get_contents($url_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					if($context!=FALSE){
						$linkedinresponse = json_decode(file_get_contents($url_with_token), true);
						$l_like+=$linkedinresponse["numLikes"];
						$l_comment+=$linkedinresponse["updateComments"]["_total"];
						$l_network=3;
					}
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$l_like,
					'share'=>$l_share,
					'comment'=>$l_comment,
					'channel'=>$l_channel_id
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==4){
					//var_dump('sumit4');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==5){
					$y_channel_id=$contentId_response[0];
					$responseObj = $contentId_response[1];
					$gooleURL = "https://www.googleapis.com/youtube/v3/videos?id=".$responseObj."&key=AIzaSyDwlUHX89WFsnePZN8UZ1ZmDOXFKvOLNyA&fields=items(id,snippet(channelId,title,categoryId),statistics)&part=snippet,statistics";
					
					$context = '';
					$mod_date= filemtime($this->filepath.$responseObj."googlejson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$responseObj."googlejson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
							$context = @file_get_contents($gooleURL);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
						$context = @file_get_contents($gooleURL);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					if($context!=FALSE){
						$abc=json_decode($context,true);
						if(count($abc['items'])>0){
							$y_like+=$abc['items'][0]['statistics']['likeCount'];
							$y_comment+=$abc['items'][0]['statistics']['commentCount'];
							$y_network=5;
						}
					}
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$y_like,
					'share'=>$y_share,
					'comment'=>$y_comment,
					'channel'=>$y_channel_id
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==6){
					//var_dump('sumit6');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==7){
					//var_dump('sumit7');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==8){
					//var_dump('sumit8');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==9){
					//var_dump('sumit9');
				}
				
			}
			
		}
		
		foreach($conntent_result as $con_key=>$singlecon){
			$content_text=Content::getContentById($singlecon['content_id']);
			$total_content_text[$singlecon['content_id']]=array(
			'text'=>$content_text->content
			);
		}
		$total_channelid=array_unique($total_channelid);
		foreach($total_channelid as $ch_key=>$channel){
			$channel_info=Channel::where('id',$channel)->first();
			$total_channel_info[$channel]=array(
			'name'=>$channel_info->name,
			'network'=>$channel_info->network_id
			);
		}
		//return Response::json($total_content_text);
		echo"<pre>";
		var_dump($total_channel_info);exit;
		
		//return View::make('response.projectInfoResponse')->with('name','Hello!')->with('id',$this->user->id)->with('total_content_text',$total_content_text)->with('total_channel_info',$total_channel_info)->with('conntent_result',$conntent_result);
	}
	
	public function getFeedsResponse(){
		$Contents_Texts = array();
		$Response_details = array();
		$Response_networkwise = array();
		$manageProject=ProjectUsers::getmanageProject($this->user->id);
		$ownProject=Project::getAllProjects($this->user->id);
		$projects = array(
			'manageProject' => $manageProject,
			'ownProject' => $ownProject
		);
		$tf_like=0;$tt_like=0;$tl_like=0;$ty_like=0;
		$tf_share=0;$tt_share=0;$tl_share=0;$ty_share=0;
		$tf_comment=0;$tt_comment=0;$tl_comment=0;$ty_comment=0;
		$tf_project=array();$tt_project=array();$tl_project=array();$ty_project=array();
		$tf_channel_id=array();$tt_channel_id=array();$tl_channel_id=array();$ty_channel_id=array();
		$datas=Publishstatus::getResponseIds($projects);
		foreach($datas[0]["project_channel_ids"] as $pro_key=>$projects)
		{
			foreach($projects[key($projects)] as $cot_key=>$content){
					//if( $net_name==$NetworkIds[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]){
				$contentId_response=explode("*", $content);
				$contentText = Content::getContentById($contentId_response[2]);
				$Contents_Texts[] = array(
					'text'=>$contentText->content,
					'network_id'=>(int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key],
					'project_id'=>key($projects),
					'response'=>$contentId_response[1],
					'auth'=>$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key],
					'conact'=>$datas[0]['project_channel_ids'][$pro_key][key($projects)][$cot_key]
				);
					//}
			}
		}
		$ch_auths = array();
		$channel = array();
		$auth = array();
		$network = array();
		foreach($Contents_Texts as $pro_key=>$Contents_Text)
		{
			$concats = explode("*",$Contents_Text['conact']);
			$channel[] = $concats[0].'*'.$Contents_Text['network_id'];
			$auth[] = $Contents_Text['auth'];
			
		}
		$ch_auths = array(
			'channel'=>array_unique($channel),
			'auth'=>array_unique($auth),
		);
		//echo "<pre>";
		//var_dump($ch_auths);exit;
		foreach($ch_auths['channel'] as $ca_key=>$ch_auth)
		{
			$c_a = explode("*",$ch_auth);
			if($c_a[1]==1)
			{
				$accessToken=(json_decode($auth[$ca_key])->access_token);
				$likesurl="https://graph.facebook.com/v2.5/me/feed?access_token=";
				$commenturl_with_token = $likesurl.$accessToken;
				$json = @file_get_contents($commenturl_with_token);
				if($json){
				$activity=json_decode($json, true);
				if($activity['data']!=null){
						foreach($activity['data'] as $jd_key=>$feed){
							//echo "<pre>";
							//var_dump(array_key_exists('story', $feed));
							if(array_key_exists('message', $feed)){
								//echo "<pre>";
								//var_dump($feed);
								$activitysave=ActivityFeeds::checkFeed($feed['id']);
								//var_dump($activitysave);
								if($activitysave){
									$tonasave=Tonality::getTonKey($feed['message']);
									//echo "<pre>";
									//var_dump($tonasave[0]['tonality']);exit;
									$feeds = new ActivityFeeds();
									$feeds->network_id = $c_a[1];
									$feeds->channel_id = $c_a[0];
									$feeds->feed_text = $feed['message'];
									$feeds->feed_id = $feed['id'];
									
									$feeds->fear = $tonasave[0]['tonality']['fear'];
									$feeds->desire = $tonasave[0]['tonality']['desire'];
									$feeds->positivity = $tonasave[0]['tonality']['positivity'];
									$feeds->negativity = $tonasave[0]['tonality']['negativity'];
									$feeds->violence = $tonasave[0]['tonality']['violence'];
									$feeds->skepticism = $tonasave[0]['tonality']['skepticism'];
									$feeds->love = $tonasave[0]['tonality']['love'];
									$feeds->hate = $tonasave[0]['tonality']['hate'];
									$feeds->keywords = $tonasave[0]['Keyword'];
									$feeds->updated_at = date('Y-m-d H:i:s', time());
									$feeds->created_at = date('Y-m-d H:i:s', strtotime($feed['created_time']));
									$feeds->save();
									var_dump('success');
								}
							}
							
						}
					}
				}
				
			}
			elseif($c_a[1]==2)
			{
				$networkInfo = DB::table('network')->where('id', 2)->first();
				$CONSUMER_KEYs = $networkInfo->api_key;
				$CONSUMER_SECRETs = $networkInfo->secret_key;
				$accessToken = (json_decode($auth[$ca_key])->access_token);
				$accesssecret = (json_decode($auth[$ca_key])->access_secret);
				\Codebird\Codebird::setConsumerKey($CONSUMER_KEYs, $CONSUMER_SECRETs);
				$cb = \Codebird\Codebird::getInstance();
				$cb->setToken($accessToken, $accesssecret);
				$reply = (array)$cb->statuses_userTimeline(); //statuses_mentionsTimeline();
				if($reply['httpstatus']==200)
				{
					foreach($reply as $r_key=>$rep){
						//echo "<pre>";
						//var_dump((array)$rep);//exit;
						if(count((array)$rep)>20){
							$activitysave=ActivityFeeds::checkFeed($rep->id);
							//var_dump($activitysave);
							if($activitysave){
								$tonasave=Tonality::getTonKey($rep->text);
								//echo "<pre>";
								//var_dump($tonasave[0]['tonality']);exit;
								$feeds = new ActivityFeeds();
								$feeds->network_id = $c_a[1];
								$feeds->channel_id = $c_a[0];
								$feeds->feed_text = $rep->text;
								$feeds->feed_id = $rep->id;
								
								$feeds->fear = $tonasave[0]['tonality']['fear'];
								$feeds->desire = $tonasave[0]['tonality']['desire'];
								$feeds->positivity = $tonasave[0]['tonality']['positivity'];
								$feeds->negativity = $tonasave[0]['tonality']['negativity'];
								$feeds->violence = $tonasave[0]['tonality']['violence'];
								$feeds->skepticism = $tonasave[0]['tonality']['skepticism'];
								$feeds->love = $tonasave[0]['tonality']['love'];
								$feeds->hate = $tonasave[0]['tonality']['hate'];
								$feeds->keywords = $tonasave[0]['Keyword'];
								$feeds->updated_at = date('Y-m-d H:i:s', time());
								$feeds->created_at = date('Y-m-d H:i:s', strtotime($rep->created_at));
								$feeds->save();
								var_dump('success');
							}
						}
					}
					//$activitysave=ActivityFeeds::saveTweetFeed($reply,$c_a[1],$c_a[0]);
					//echo "<pre>";
					//var_dump($reply[0]->text);
				}
			
				
			}
			
		}
		
	}
	
	public function getChannelResDetail(){
		$result = array();
		$channel_res = array();
		$channel_ids = array();
		$Contents_Texts = array();
		$manageProject=ProjectUsers::getmanageProject($this->user->id);
		$ownProject=Project::getAllProjects1($this->user->id);
		$projects = array(
			'manageProject' => $manageProject,
			'ownProject' => $ownProject
		);
		$datas=Publishstatus::getResponseIds($projects);
		foreach($datas[0]["project_channel_ids"] as $pro_key=>$projects)
		{
			foreach($projects[key($projects)] as $cot_key=>$content){
				$contentId_response=explode("*", $content);
				$channel_ids[] = $contentId_response[0];
				$Contents_Texts[] = array(
					'network_id'=>(int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key],
					'project_id'=>key($projects),
					'channel_id'=>$contentId_response[0],
					'conact'=>$datas[0]['project_channel_ids'][$pro_key][key($projects)][$cot_key]
				);
			}
		}
		foreach ($Contents_Texts as $data) {
			$id = $data['channel_id'];
			if (isset($result[$id])) {
				$result[$id]['proid'][] = $data['project_id'];
				$result[$id]['conct'][] = $data['conact'];
			} 
			else {
				$result[$id] = array(
				'proid'=>array($data['project_id']),
				'conct'=>array($data['conact']),
				);
			}
		}
		//echo "<pre>";
		//var_dump($result);exit;
		foreach($result as $r_key=> $channel){
			$c_like=0;$c_share=0;$c_comment=0;$twi_status=1;$rereply = array();
			foreach($channel['conct'] as $c_key=> $post_res){
				$p_like=0;$p_share=0;$p_comment=0;
				$contentId_response=explode("*", $post_res);
				$channel_detail = Channel::where('id', $contentId_response[0])->first();
				//var_dump($channel_detail->network_id);exit;
				if($channel_detail->network_id==1){
					$responseObj = $contentId_response[1];
					$token= $channel_detail->auth_detail;
					//Here get the likes
					$likesurl="https://graph.facebook.com/likes?id=".$responseObj."&access_token=";
					$accessToken=(json_decode($token)->access_token);
					$likesurl_with_token = $likesurl.$accessToken ;
					//$context = @file_get_contents($likesurl_with_token);
					
					$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."likewithjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."likewithjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
							$context = @file_get_contents($likesurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
						$context = @file_get_contents($likesurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					
					if($context!=FALSE){
						$jsonlikes = file_get_contents($likesurl_with_token);
						$likesResponses=json_decode($jsonlikes, true);
						$userLikes = "";
						$likescount=2;
						$totalcount=0;
						if($likesResponses['data']!=null && count($likesResponses)>=2){
							$totalcount=count($likesResponses['data']);
						}
						$c_like+=$totalcount;
						$p_like+=$totalcount;
					}
					//shared post
					$data=explode('_', $responseObj);
					$p_id=(count($data)>1)?$data[1]:$data[0];
					$sharedurl="https://graph.facebook.com/".$p_id."/sharedposts?access_token=";
					$sharedurlurl_with_token = $sharedurl.$accessToken ;
					
					$context = '';
					$mod_date= filemtime($accessToken."sharedpostsfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($accessToken."sharedpostsfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
							$context = @file_get_contents($sharedurlurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
						$context = @file_get_contents($sharedurlurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					
					
					if($context!=FALSE){
						$jsonshares = file_get_contents($sharedurlurl_with_token);
						$sharesResponses=json_decode($jsonshares, true);
						$c_share+=count($sharesResponses['data']);
						$p_share+=count($sharesResponses['data']);
						$sharedhistory = array();
						if($sharesResponses['data']!=null && count($sharesResponses)>=2){
							foreach($sharesResponses['data'] as $sharesResponse){
								$sharedhistory[]= array(
									'story' => $sharesResponse['story'], 
								);
							}
						}
						else{
						 $sharedhistory[]= array(
								'story' =>'no any share your post.', 
							);
						}
					}
					//Here get the comments
					$commenturl="https://graph.facebook.com/".$responseObj."/comments?summary=true&access_token=";
					$commenturl_with_token = $commenturl.$accessToken ;
					
					$json = '';
					$mod_date= filemtime($accessToken."commentsfacebookjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($accessToken."commentsfacebookjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($accessToken."commentsfacebookjson.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
							fwrite($Userfile, $json);
							fclose($Userfile);
						} else {
								$Userfile = fopen($accessToken."commentsfacebookjson.txt", "r");
								$json = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($accessToken."commentsfacebookjson.txt", "w");
						$json = @file_get_contents($commenturl_with_token);
						fwrite($Userfile, $json);
						fclose($Userfile);
					}
					
					
					
					if($json!=FALSE){
						$commentResponses=json_decode($json, true);
						if(count($commentResponses)>1){
							$c_comment+=$commentResponses['summary']['total_count'];
							$p_comment+=$commentResponses['summary']['total_count'];
						}
					}
				}
				elseif($channel_detail->network_id==2){
					$networkInfo = DB::table('network')->where('id', $channel_detail->network_id)->first();
					$CONSUMER_KEYs = $networkInfo->api_key;
					$CONSUMER_SECRETs = $networkInfo->secret_key;
					$token=$channel_detail->auth_detail;
					if ($token != null) {
						$responseObj = $contentId_response[1];
						if ($responseObj != null) {
							$accessToken = (json_decode($token)->access_token);
							$accesssecret = (json_decode($token)->access_secret);
							\Codebird\Codebird::setConsumerKey($CONSUMER_KEYs, $CONSUMER_SECRETs);
							$cb = \Codebird\Codebird::getInstance();
							$cb->setToken($accessToken, $accesssecret);
							$reply = $cb->statuses_show_ID('id=' . $responseObj);
							//var_dump($responseObj);
							if($twi_status==1){
								//var_dump('in');
								$rereply = (array)$cb->statuses_mentionsTimeline();
								$twi_status=0;
								//var_dump(count($rereply));
							}
							$tweetreply = array();
							if(count($rereply)>3){
								foreach ($rereply as $rep) {
									if (count((array)$rep) > 3 && $responseObj == $rep->in_reply_to_status_id_str) {
										$tweetreply[] = array(
											'reply_text' => $rep->text,
											'twittes_id' => $rep->id_str,
											'twittes_screen_name' => $rep->user->name,
										);
									}
								}
							}
							if ($reply->httpstatus == 200) {
								$c_like+=$reply->favorite_count;
								$p_like+=$reply->favorite_count;
								$c_share+=$reply->retweet_count;
								$p_share+=$reply->retweet_count;
								$c_comment+=count($tweetreply);
								$p_comment+=count($tweetreply);
							}
						}
					}
				}
				elseif($channel_detail->network_id==3){
					$responseObj = $contentId_response[1];
					$token=$channel_detail->auth_detail;
					$urllinked="https://api.linkedin.com/v1/companies/2414183/updates/key=".$responseObj."?oauth2_access_token=";
					$accessToken=(json_decode($token)->access_token);
					$url_with_token = $urllinked.$accessToken."&format=json";
					
					
					
					$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."linkedinjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."linkedinjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
							$context = @file_get_contents($url_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
						$context = @file_get_contents($url_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					
					
					
					
					if($context!=FALSE){
						$linkedinresponse = json_decode(file_get_contents($url_with_token), true);
						$c_like+=$linkedinresponse["numLikes"];
						$p_like+=$linkedinresponse["numLikes"];
						$c_comment+=$linkedinresponse["updateComments"]["_total"];
						$p_comment+=$linkedinresponse["updateComments"]["_total"];
					}
				}
				elseif($channel_detail->network_id==4){
				//var_dump('sumit4');
				}
				elseif($channel_detail->network_id==5){
					$responseObj = $contentId_response[1];
					
					$gooleURL = "https://www.googleapis.com/youtube/v3/videos?id=".$responseObj."&key=AIzaSyDwlUHX89WFsnePZN8UZ1ZmDOXFKvOLNyA&fields=items(id,snippet(channelId,title,categoryId),statistics)&part=snippet,statistics";
					
					$context = '';
					$mod_date= filemtime($this->filepath.$responseObj."googlejson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$responseObj."googlejson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
							$context = @file_get_contents($gooleURL);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
						$context = @file_get_contents($gooleURL);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					
					
					//$gooleURL = "https://www.googleapis.com/youtube/v3/videos?id=".$responseObj."&key=AIzaSyDwlUHX89WFsnePZN8UZ1ZmDOXFKvOLNyA&fields=items(id,snippet(channelId,title,categoryId),statistics)&part=snippet,statistics";
					
					$context = '';
					$mod_date= filemtime($this->filepath.$responseObj."googlejson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$responseObj."googlejson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
							$context = @file_get_contents($gooleURL);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
						$context = @file_get_contents($gooleURL);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					
					
					if($context!=FALSE){
						$abc=json_decode($context,true);
						if(count($abc['items'])>0){
							$c_like+=$abc['items'][0]['statistics']['likeCount'];
							$p_like+=$abc['items'][0]['statistics']['likeCount'];
							$c_comment+=$abc['items'][0]['statistics']['commentCount'];
							$p_comment+=$abc['items'][0]['statistics']['commentCount'];
						}
					}
				}
				elseif($channel_detail->network_id==6){
					//var_dump('sumit6');
				}
				elseif($channel_detail->network_id==7){
					//var_dump('sumit7');
				}
				elseif($channel_detail->network_id==8){
					//var_dump('sumit8');
				}
				elseif($channel_detail->network_id==9){
					//var_dump('sumit9');
				}
				$updatePublished = Publishstatus::where('channel_id', $r_key)->where('response', $contentId_response[1])->update(array('likes' => $p_like, 'shares' => $p_share, 'comments' => $p_comment));
			}
			$channel_res[$r_key] = array(
				'like'=>$c_like,
				'share'=>$c_share,
				'comment'=>$c_comment,
			);
		}
		foreach($channel_res as $cr_key=>$res){
			//var_dump($res["like"]);exit;
			$checkCh = ResChannelAlert::where('channel_id', $cr_key)->first();
			if($checkCh){
				$update = ResChannelAlert::where('channel_id', $cr_key)->where('user_id', $this->user->id)->update(array('like_count' => $res["like"], 'share_count' => $res["share"], 'comment_count' => $res["comment"]));
				var_dump("updated Successfully");
			}
			else{
				$reschannelalert = new ResChannelAlert;
				$reschannelalert->user_id = $this->user->id;
				$reschannelalert->channel_id = $cr_key;
				$reschannelalert->like_count = $res["like"];
				$reschannelalert->share_count = $res["share"];
				$reschannelalert->comment_count = $res["comment"];
				$reschannelalert->created_at = date('Y-m-d H:i:s', time());
				$reschannelalert->updated_at = date('Y-m-d H:i:s', time());
				$reschannelalert->save();
				var_dump("saved Successfully");
			}
		}
		
		echo "<pre>";
		//var_dump($channel_res);
		//var_dump($c_like);var_dump($c_comment);var_dump($c_share);
			exit;
			echo "<pre>";
			var_dump($channel['conct']);exit;
		
	}
	public function getProjectView(){
		$channel_ids = array();
		$Contents_Texts = array();
		$manageProject=ProjectUsers::getmanageProject($this->user->id);
		$ownProject=Project::getAllProjects($this->user->id, 24);
		$projects = array(
			'manageProject' => $manageProject,
			'ownProject' => $ownProject
		);
		$datas=Publishstatus::getResponseIds($projects);
		$pro_result = array();
		foreach($datas[0]["project_channel_ids"] as $pro_key=>$projects)
		{
			foreach($projects[key($projects)] as $cot_key=>$content){
				$contentId_response=explode("*", $content);
				//$channel_ids[] = $contentId_response[0];
				if (isset($pro_result[key($projects)])) {
					if (isset($pro_result[key($projects)][$contentId_response[0]])) {
						//$pro_result[key($projects)][$contentId_response[0]]['proid'][] = key($projects);
						$pro_result[key($projects)][$contentId_response[0]]['conct'][] = $content;
					}
					else {
						$pro_result[key($projects)][$contentId_response[0]] = array(
						//'proid'=>array(key($projects)),
						'conct'=>array($content),
						);
					}
				}
				else {
						$pro_result[key($projects)] = array(
							$contentId_response[0]=> array(
							'conct'=>(array)$content
							)
						);
					}
			}
		}
		echo "<pre>";
		//var_dump(array_unique($channel_ids));exit;
		var_dump($pro_result);exit;
	}
	
	public function Unknown_activity_feed(){
		$channel_ids = array();
		$Contents_Texts = array();
		$manageProject=ProjectUsers::getmanageProject($this->user->id);
		$ownProject=Project::getAllProjects1($this->user->id);
		$projects = array(
			'manageProject' => $manageProject,
			'ownProject' => $ownProject
		);
		$datas=Publishstatus::getResponseIds($projects);
		foreach($datas[0]["project_channel_ids"] as $pro_key=>$projects)
		{
			foreach($projects[key($projects)] as $cot_key=>$content){
				$contentId_response=explode("*", $content);
				$Contents_Texts[] = array(
					'channel_id'=>$contentId_response[0],
				);
			}
		}
		$result = array();
		foreach ($Contents_Texts as $data) {
			$id = $data['channel_id'];
				$result[$id] = array(
				'UNKNOWN'=>ActivityFeeds::getFeedsByChannel($id),
				);
			}
	
		echo "<pre>";
		var_dump($result);exit;
	}
	
	public function getAllOrg(){
		$orgs = array();
		$orgarr = array('-Organization-');
		$ownOrg = Organization::getcreatedOrg($this->user->id);
		$otrOrg = Organization::getAssociatedOrg($this->user->email);
		/*echo"<pre>";
		var_dump($ownOrg);
		var_dump($otrOrg);
		exit;*/
		$orgs = array(
			'ownOrg' => $ownOrg,
			'otrOrg' => $otrOrg,
		);
		foreach($orgs as $org){
			foreach($org as $o){
				$orgarr[$o['org_id']] = $o['org_name'];
			}
		}
		//return Response::json($orgarr);
		return View::make('response.drop')->with(array('connected_platforms' => $orgarr));
	}
	
	public function getOrgs(){
		error_reporting(E_ALL & ~E_NOTICE);
		$orgs = array();
		$orgarr = array('-Organization-');
		$ownOrg = Organization::getcreatedOrg($this->user->id);
	
		$otrOrg = Organization::getAssociatedOrg($this->user->id);		
		$orgs = array(
			'ownOrg' => $ownOrg,
			'otrOrg' => $otrOrg,
		);
		foreach($orgs as $org){
			foreach($org as $o){
				$orgarr[$o['org_id']] = $o['org_name'];
			}
		}
		return Response::json($orgarr);
	}
	
	public function getOrgProjects(){
		$organization_id =Input::get('org_id');
		$projects = array();
		$entitledProject = array();
		$proarr = array();
		$manageProject=ProjectUsers::getmanageProject($this->user->id);
		foreach($manageProject as $mp_key=>$project){
			$proInfo = Project::where('id', $project)->first();
			if($proInfo){
				if($proInfo->org_id == $organization_id){
					$entitledProject[] = $proInfo->id;
				}
			}
		}
		$ownProject=Project::getAllProjects($this->user->id, $organization_id);
		$projects = array(
			'manageProject' => $entitledProject,
			'ownProject' => $ownProject
		);
		foreach($projects as $pro){
			foreach($pro as $p){
				$pInfo = Project::where('id', $p)->first();
				if($pInfo){
					$proarr[$pInfo->id] = $pInfo->title;
				}
			}
		}
		return Response::json($proarr);
	}
	
	
	public function getResponseAlerts(){
		
		return View::make('response.responseAlerts');
	}
	
	public function getResponseActivity(){
		
		return View::make('response.responseActivity');
	}
	
	public function getTimeDiffrence($p_date){
		$diffHour = 'UNKNOWN Time';
		$dteStart = new DateTime($p_date); 
		$dteEnd   = new DateTime(date('Y-m-d H:i:s', time()));
		$dteDiff  = $dteStart->diff($dteEnd); 
		//var_dump($dteDiff->m);exit;
		if($dteDiff->y>0){
			$diffHour = $dteDiff->format("%y year %m months %d days %H:%I:%S ago");
		}
		elseif($dteDiff->m>0){
			$diffHour = $dteDiff->format("%m months %d days %H:%I:%S ago");
		}
		elseif($dteDiff->d>0){
			$diffHour = $dteDiff->format("%d days %H:%I:%S ago");
		}
		elseif($dteDiff->h>0){
			$diffHour = $dteDiff->format("%H:%I:%S ago");
		}
		elseif($dteDiff->i>0){
			$diffHour = $dteDiff->format("%I minutes ago");
		}
		elseif($dteDiff->s>0){
			$diffHour = $dteDiff->format("%S seconds ago");
		}
		
		return $diffHour;
	}
	
	public function getProjectView1(){
		$AllprojectAllInfo = array();
		$channel_ids = array();
		$Contents_Texts = array();
		$manageProject= array();
		$conntent_result = array();
		$total_channelid = array();
		$total_content_text = array();
		$total_channel_info = array();
		$ownProject= array_unique(Input::get('pro_ids'));
		
		$projects = array(
			'manageProject' => $manageProject,
			'ownProject' => $ownProject
		);
		$datas=Publishstatus::getResponseIds($projects);
		foreach($datas[0]["project_channel_ids"] as $pro_key=>$projects)
		{
			foreach($projects[key($projects)] as $cot_key=>$content){
			$f_like=0;$t_like=0;$l_like=0;$y_like=0;
			$f_share=0;$t_share=0;$l_share=0;$y_share=0;
			$f_comment=0;$t_comment=0;$l_comment=0;$y_comment=0;
			$f_network=0;$t_network=0;$l_network=0;$y_network=0;
			$f_channel_id=0;$t_channel_id=0;$l_channel_id=0;$y_channel_id=0;
				$contentId_response=explode("*", $content);
				$total_channelid[]=$contentId_response[0];
				if((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==1){
					$picResponses = array();
					$picUrl = '';
					$f_channel_id=$contentId_response[0];
					$responseObj = $contentId_response[1];
					$token=$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key];
					//Here get the likes
					$likesurl="https://graph.facebook.com/likes?id=".$responseObj."&access_token=";
					$accessToken=(json_decode($token)->access_token);
					$likesurl_with_token = $likesurl.$accessToken ;
					//$context = @file_get_contents($likesurl_with_token);
					$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."likewithjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."likewithjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
							$context = @file_get_contents($likesurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
						$context = @file_get_contents($likesurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					
					
					
					if($context!=FALSE){
						$jsonlikes = file_get_contents($likesurl_with_token);
						$likesResponses=json_decode($jsonlikes, true);
						$userLikes = "";
						$likescount=2;
						$totalcount=0;
						if($likesResponses['data']!=null && count($likesResponses)>=2){
							$totalcount=count($likesResponses['data']);
						}
						$f_like+=$totalcount;
						$f_network=1;
					}
					//shared post
					$data=explode('_', $responseObj);
					$p_id=(count($data)>1)?$data[1]:$data[0];
					$sharedurl="https://graph.facebook.com/".$p_id."/sharedposts?access_token=";
				$sharedurlurl_with_token = $sharedurl.$accessToken ;
$context = '';
					$mod_date= filemtime($accessToken."sharedpostsfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($accessToken."sharedpostsfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
							$context = @file_get_contents($sharedurlurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
						$context = @file_get_contents($sharedurlurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					if($context!=FALSE){
						$jsonshares = file_get_contents($sharedurlurl_with_token);
						$sharesResponses=json_decode($jsonshares, true);
						$f_share+=count($sharesResponses['data']);
						$f_network=1;
						$sharedhistory = array();
						if($sharesResponses['data']!=null && count($sharesResponses)>=2){
							foreach($sharesResponses['data'] as $sharesResponse){
								$sharedhistory[]= array(
									'story' => $sharesResponse['story'], 
								);
							}
						}
						else{
						 $sharedhistory[]= array(
								'story' =>'no any share your post.', 
							);
						}
					}
					//Here get the comments
					$commenturl="https://graph.facebook.com/".$responseObj."/comments?summary=true&access_token=";
					$commenturl_with_token = $commenturl.$accessToken ;

					
					$json = '';
					$mod_date= filemtime($accessToken."commentsfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($accessToken."commentsfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($accessToken."commentsfacebook.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
							fwrite($Userfile, $json);
							fclose($Userfile);
						} else {
								$Userfile = fopen($accessToken."commentsfacebook.txt", "r");
								$json = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($accessToken."commentsfacebook.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
						fwrite($Userfile, $json);
						fclose($Userfile);
					}
					if($json!=FALSE){
						$commentResponses=json_decode($json, true);
						if(count($commentResponses)>1){
							$f_comment+=$commentResponses['summary']['total_count'];
						}
						$f_network=1;
					}
					$forPicOnly = "https://graph.facebook.com/v2.1/me/?fields=picture,name&access_token=".$accessToken."";
					$jsonPic = @file_get_contents($forPicOnly);
					if($jsonPic!=FALSE){
						$picResponses=json_decode($jsonPic, true);
						$picUrl = $picResponses['picture']['data']['url'];
					}
					$diffHour = $this->getTimeDiffrence($contentId_response[3]);
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$f_like,
					'share'=>$f_share,
					'comment'=>$f_comment,
					'channel'=>$f_channel_id,
					'comment_by_pic' => $picUrl,
					'hour'=> $diffHour,
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==2){
					$picUrl = '';
					$t_channel_id=$contentId_response[0];
					$networkInfo = DB::table('network')->where('id', 2)->first();
					$CONSUMER_KEYs = $networkInfo->api_key;
					$CONSUMER_SECRETs = $networkInfo->secret_key;
					$token=$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key];
					if ($token != null) {
						$responseObj = $contentId_response[1];
						if ($responseObj != null) {
							$accessToken = (json_decode($token)->access_token);
							$accesssecret = (json_decode($token)->access_secret);
							\Codebird\Codebird::setConsumerKey($CONSUMER_KEYs, $CONSUMER_SECRETs);
							$cb = \Codebird\Codebird::getInstance();
							$cb->setToken($accessToken, $accesssecret);
							$reply = $cb->statuses_show_ID('id=' . $responseObj);
							$rereply = (array)$cb->statuses_mentionsTimeline();
							$tweetreply = array();
							foreach ($rereply as $rep) {
								if (count((array)$rep) > 3 && $responseObj == $rep->in_reply_to_status_id_str) {
									$tweetreply[] = array(
										'reply_text' => $rep->text,
										'twittes_id' => $rep->id_str,
										'twittes_screen_name' => $rep->user->name,
									);
									$ts_comment='Yes';
								}
							}
							if ($reply->httpstatus == 200) {
								$t_like+=$reply->favorite_count;
								$t_share+=$reply->retweet_count;
								$t_comment+=count($tweetreply);
								$picUrl = $reply->user->profile_image_url;
								
								$t_network=2;
							}
						}
					}
					$diffHour = $this->getTimeDiffrence($contentId_response[3]);
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$t_like,
					'share'=>$t_share,
					'comment'=>$t_comment,
					'channel'=>$t_channel_id,
					'comment_by_pic' => $picUrl,
					'hour'=> $diffHour,
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==3){
					$picUrl = '';
					$l_channel_id=$contentId_response[0];
					$responseObj = $contentId_response[1];
					$token=$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key];
					$urllinked="https://api.linkedin.com/v1/companies/2414183/updates/key=".$responseObj."?oauth2_access_token=";
						$accessToken=(json_decode($token)->access_token);
						$url_with_token = $urllinked.$accessToken."&format=json";
						$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."linkedinjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."linkedinjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
							$context = @file_get_contents($url_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
						$context = @file_get_contents($url_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					if($context!=FALSE){
						$linkedinresponse = json_decode(file_get_contents($url_with_token), true);
						//echo"<pre>";var_dump($linkedinresponse['updateContent']["person"]["pictureUrl"]);exit;
						$l_like+=$linkedinresponse["numLikes"];
						$l_comment+=$linkedinresponse["updateComments"]["_total"];
						$picUrl = $linkedinresponse['updateContent']["person"]["pictureUrl"];
						$l_network=3;
					}
					$diffHour = $this->getTimeDiffrence($contentId_response[3]);
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$l_like,
					'share'=>$l_share,
					'comment'=>$l_comment,
					'channel'=>$l_channel_id,
					'comment_by_pic' => $picUrl,
					'hour'=> $diffHour,
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==4){
					//var_dump('sumit4');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==5){
					$picUrl = '';
					$y_dislike=0;
					$y_views_count=0;
					$y_channel_id=$contentId_response[0];
					$responseObj = $contentId_response[1];
					$gooleURL = "https://www.googleapis.com/youtube/v3/videos?id=".$responseObj."&key=AIzaSyDwlUHX89WFsnePZN8UZ1ZmDOXFKvOLNyA&fields=items(id,snippet(channelId,title,categoryId),statistics)&part=snippet,statistics";
					
					$context = '';
					$mod_date= filemtime($this->filepath.$responseObj."googlejson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$responseObj."googlejson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
							$context = @file_get_contents($gooleURL);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
						$context = @file_get_contents($gooleURL);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					if($context!=FALSE){
						$abc=json_decode($context,true);
						if(count($abc['items'])>0){
							$y_like+=$abc['items'][0]['statistics']['likeCount'];
							$y_comment+=$abc['items'][0]['statistics']['commentCount'];
							$y_dislike+=$abc['items'][0]['statistics']['dislikeCount'];
							$y_views_count+=$abc['items'][0]['statistics']['viewCount'];
							$y_network=5;
						}
					}
					$diffHour = $this->getTimeDiffrence($contentId_response[3]);
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$y_like,
					'share'=>$y_share,
					'comment'=>$y_comment,
					'channel'=>$y_channel_id,
					'comment_by_pic' => $picUrl,
					'hour'=> $diffHour,
					'dislike'=>$y_dislike,
					'views'=>$y_views_count,
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==6){
					//var_dump('sumit6');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==7){
					//var_dump('sumit7');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==8){
					//var_dump('sumit8');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==9){
					//var_dump('sumit9');
				}
				
			}
			
			foreach($conntent_result as $con_key=>$singlecon){
				$content_text=Content::getContentById($singlecon['content_id']);
				$content_status=Publishstatus::where('content_id', $singlecon['content_id'])->where('channel_id', $singlecon['channel'])->first();
				$media_text=Attachment::where('content_id', $singlecon['content_id'])->get();
				$content_file = array();
				if(sizeof($media_text)>0){
					foreach($media_text as $media){
						
							$content_file[]=array(
								'file_type'=>$media->type,
								'file_name'=>$media->filename
								);
						
					}
					$total_content_text[$singlecon['content_id']]=array(
						'text'=>$content_text->content,
						'time'=>$content_status->updated_at,
						'file_name'=>$content_file,
					);
				}else{
					$total_content_text[$singlecon['content_id']]=array(
						'text'=>$content_text->content,
						'time'=>$content_status->updated_at,
						'file_name'=>array(),
					);
				}	
			}
			$total_channelid=array_unique($total_channelid);
			foreach($total_channelid as $ch_key=>$channel){
				$channel_info=Channel::where('id',$channel)->first();
				if($channel_info){
					$netInfo = Network::where('id', $channel_info->network_id)->first();
					$total_channel_info[$channel]=array(
					'id'=>$channel,
					'name'=>$channel_info->name,
					'network'=>strtolower($netInfo->platform),
					);
				}	
			}
			
			$AllprojectAllInfo[key($projects)] = array(
				'total_content_text' => $total_content_text,
				'total_channel_info' => $total_channel_info,
				'content_result' => $conntent_result
			
			);
			$total_content_text = array();
			$total_channel_info = array();
			$conntent_result = array();
			
		}
		//echo"<pre>";
		//var_dump($AllprojectAllInfo);exit;
		return Response::json($AllprojectAllInfo);
	}

	public function getProjectView2(){ 
		$AllprojectAllInfo = array();
		$channel_ids = array();
		$Contents_Texts = array();
		$manageProject= array();
		$conntent_result = array();
		$total_channelid = array();
		$total_content_text = array();
		$total_channel_info = array();
			
		$ownProject= Input::get('pro_ids');
		$ownProject = ProjectUsers::getmanageProject($this->user->id);
		$projects = array(
			'manageProject' => $manageProject,
			'ownProject' => $ownProject
		);
		$datas=Publishstatus::getResponseIds($projects);
		foreach($datas[0]["project_channel_ids"] as $pro_key=>$projects)
		{
			foreach($projects[key($projects)] as $cot_key=>$content){
			$f_like=0;$t_like=0;$l_like=0;$y_like=0;
			$f_share=0;$t_share=0;$l_share=0;$y_share=0;
			$f_comment=0;$t_comment=0;$l_comment=0;$y_comment=0;
			$f_network=0;$t_network=0;$l_network=0;$y_network=0;
			$f_channel_id=0;$t_channel_id=0;$l_channel_id=0;$y_channel_id=0;
				$contentId_response=explode("*", $content);
				$total_channelid[]=$contentId_response[0];
				if((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==1){
					$picResponses = array();
					$picUrl = '';
					$f_channel_id=$contentId_response[0];
					$responseObj = $contentId_response[1];
					$token=$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key];
					//Here get the likes
					$likesurl="https://graph.facebook.com/likes?id=".$responseObj."&access_token=";
					$accessToken=(json_decode($token)->access_token);
					$likesurl_with_token = $likesurl.$accessToken ;
					//$context = @file_get_contents($likesurl_with_token);
					
					$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."likewithjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."likewithjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
							$context = @file_get_contents($likesurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
						$context = @file_get_contents($likesurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					
					if($context!=FALSE){
						$jsonlikes = file_get_contents($likesurl_with_token);
						$likesResponses=json_decode($jsonlikes, true);
						$userLikes = "";
						$likescount=2;
						$totalcount=0;
						if($likesResponses['data']!=null && count($likesResponses)>=2){
							$totalcount=count($likesResponses['data']);
						}
						$f_like+=$totalcount;
						$f_network=1;
					}
					//shared post
					$data=explode('_', $responseObj);
					$p_id=(count($data)>1)?$data[1]:$data[0];
					$sharedurl="https://graph.facebook.com/".$p_id."/sharedposts?access_token=";
				$sharedurlurl_with_token = $sharedurl.$accessToken ;
$context = '';
					$mod_date= filemtime($accessToken."sharedpostsfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($accessToken."sharedpostsfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
							$context = @file_get_contents($sharedurlurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
						$context = @file_get_contents($sharedurlurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					if($context!=FALSE){
						$jsonshares = file_get_contents($sharedurlurl_with_token);
						$sharesResponses=json_decode($jsonshares, true);
						$f_share+=count($sharesResponses['data']);
						$f_network=1;
						$sharedhistory = array();
						if($sharesResponses['data']!=null && count($sharesResponses)>=2){
							foreach($sharesResponses['data'] as $sharesResponse){
								$sharedhistory[]= array(
									'story' => $sharesResponse['story'], 
								);
							}
						}
						else{
						 $sharedhistory[]= array(
								'story' =>'no any share your post.', 
							);
						}
					}
					//Here get the comments
					$commenturl="https://graph.facebook.com/".$responseObj."/comments?summary=true&access_token=";
					$commenturl_with_token = $commenturl.$accessToken ;

					
					$json = '';
					$mod_date= filemtime($this->filepath.$accessToken."graphfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."graphfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."graphfacebook.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
							fwrite($Userfile, $json);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."graphfacebook.txt", "r");
								$json = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."graphfacebook.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
						fwrite($Userfile, $json);
						fclose($Userfile);
					}
					if($json!=FALSE){
						$commentResponses=json_decode($json, true);
						if(count($commentResponses)>1){
							$f_comment+=$commentResponses['summary']['total_count'];
						}
						$f_network=1;
					}
					$forPicOnly = "https://graph.facebook.com/v2.1/me/?fields=picture,name&access_token=".$accessToken."";
					$jsonPic = @file_get_contents($forPicOnly);
					if($jsonPic!=FALSE){
						$picResponses=json_decode($jsonPic, true);
						$picUrl = $picResponses['picture']['data']['url'];
					}
					$diffHour = $this->getTimeDiffrence($contentId_response[3]);
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$f_like,
					'share'=>$f_share,
					'comment'=>$f_comment,
					'channel'=>$f_channel_id,
					'comment_by_pic' => $picUrl,
					'hour'=> $diffHour,
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==2){
					$picUrl = '';
					$t_channel_id=$contentId_response[0];
					$networkInfo = DB::table('network')->where('id', 2)->first();
					$CONSUMER_KEYs = $networkInfo->api_key;
					$CONSUMER_SECRETs = $networkInfo->secret_key;
					$token=$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key];
					if ($token != null) {
						$responseObj = $contentId_response[1];
						if ($responseObj != null) {
							$accessToken = (json_decode($token)->access_token);
							$accesssecret = (json_decode($token)->access_secret);
							\Codebird\Codebird::setConsumerKey($CONSUMER_KEYs, $CONSUMER_SECRETs);
							$cb = \Codebird\Codebird::getInstance();
							$cb->setToken($accessToken, $accesssecret);
							$reply = $cb->statuses_show_ID('id=' . $responseObj);
							$rereply = (array)$cb->statuses_mentionsTimeline();
							$tweetreply = array();
							foreach ($rereply as $rep) {
								if (count((array)$rep) > 3 && $responseObj == $rep->in_reply_to_status_id_str) {
									$tweetreply[] = array(
										'reply_text' => $rep->text,
										'twittes_id' => $rep->id_str,
										'twittes_screen_name' => $rep->user->name,
									);
									$ts_comment='Yes';
								}
							}
							if ($reply->httpstatus == 200) {
								$t_like+=$reply->favorite_count;
								$t_share+=$reply->retweet_count;
								$t_comment+=count($tweetreply);
								$picUrl = $reply->user->profile_image_url;
								
								$t_network=2;
							}
						}
					}
					$diffHour = $this->getTimeDiffrence($contentId_response[3]);
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$t_like,
					'share'=>$t_share,
					'comment'=>$t_comment,
					'channel'=>$t_channel_id,
					'comment_by_pic' => $picUrl,
					'hour'=> $diffHour,
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==3){
					$picUrl = '';
					$l_channel_id=$contentId_response[0];
					$responseObj = $contentId_response[1];
					$token=$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key];
					$urllinked="https://api.linkedin.com/v1/companies/2414183/updates/key=".$responseObj."?oauth2_access_token=";
						$accessToken=(json_decode($token)->access_token);
						$url_with_token = $urllinked.$accessToken."&format=json";
						$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."linkedinjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."linkedinjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
							$context = @file_get_contents($url_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
						$context = @file_get_contents($url_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					if($context!=FALSE){
						$linkedinresponse = json_decode(file_get_contents($url_with_token), true);
						//echo"<pre>";var_dump($linkedinresponse['updateContent']["person"]["pictureUrl"]);exit;
						$l_like+=$linkedinresponse["numLikes"];
						$l_comment+=$linkedinresponse["updateComments"]["_total"];
						$picUrl = $linkedinresponse['updateContent']["person"]["pictureUrl"];
						$l_network=3;
					}
					$diffHour = $this->getTimeDiffrence($contentId_response[3]);
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$l_like,
					'share'=>$l_share,
					'comment'=>$l_comment,
					'channel'=>$l_channel_id,
					'comment_by_pic' => $picUrl,
					'hour'=> $diffHour,
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==4){
					//var_dump('sumit4');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==5){
					$picUrl = '';
					$y_channel_id=$contentId_response[0];
					$responseObj = $contentId_response[1];
					$gooleURL = "https://www.googleapis.com/youtube/v3/videos?id=".$responseObj."&key=AIzaSyDwlUHX89WFsnePZN8UZ1ZmDOXFKvOLNyA&fields=items(id,snippet(channelId,title,categoryId),statistics)&part=snippet,statistics";
					
					$context = '';
					$mod_date= filemtime($this->filepath.$responseObj."googlejson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$responseObj."googlejson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
							$context = @file_get_contents($gooleURL);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
						$context = @file_get_contents($gooleURL);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					if($context!=FALSE){
						$abc=json_decode($context,true);
						if(count($abc['items'])>0){
							$y_like+=$abc['items'][0]['statistics']['likeCount'];
							$y_comment+=$abc['items'][0]['statistics']['commentCount'];
							$y_network=5;
						}
					}
					$diffHour = $this->getTimeDiffrence($contentId_response[3]);
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$y_like,
					'share'=>$y_share,
					'comment'=>$y_comment,
					'channel'=>$y_channel_id,
					'comment_by_pic' => $picUrl,
					'hour'=> $diffHour,
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==6){
					//var_dump('sumit6');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==7){
					//var_dump('sumit7');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==8){
					//var_dump('sumit8');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==9){
					//var_dump('sumit9');
				}
				
			}
			
			foreach($conntent_result as $con_key=>$singlecon){
				$content_text=Content::getContentById($singlecon['content_id']);
				$content_status=Publishstatus::where('content_id', $singlecon['content_id'])->where('channel_id', $singlecon['channel'])->first();
				$media_text=Attachment::where('content_id', $singlecon['content_id'])->get();
				$content_file = array();
				if(sizeof($media_text)>0){
					foreach($media_text as $media){
						$content_file[]=array(
							'file_type'=>$media->type,
							'file_name'=>$media->filename
						);
					}
					$total_content_text[$singlecon['content_id']]=array(
						'text'=>$content_text->content,
						'time'=>$content_status->updated_at,
						'file_name'=>$content_file,
					);
				}else{
					$total_content_text[$singlecon['content_id']]=array(
						'text'=>$content_text->content,
						'time'=>$content_status->updated_at,
						'file_name'=>array(),
					);
				}
				
			}
			$total_channelid=array_unique($total_channelid);
			foreach($total_channelid as $ch_key=>$channel){
				$channel_info=Channel::where('id',$channel)->first();
				if($channel_info){
					$netInfo = Network::where('id', $channel_info->network_id)->first();
					$total_channel_info[$channel]=array(
					'id'=>$channel,
					'name'=>$channel_info->name,
					'network'=>strtolower($netInfo->platform),
					);
				}	
			}
			
			$AllprojectAllInfo[key($projects)] = array(
				'total_content_text' => $total_content_text,
				'total_channel_info' => $total_channel_info,
				'content_result' => $conntent_result
			
			);
			$total_content_text = array();
			$total_channel_info = array();
			$conntent_result = array();
			
		}
		//echo"<pre>";
		//var_dump($AllprojectAllInfo);exit;
		return Response::json($AllprojectAllInfo);	
		
	}
	
	public function getseeReplyComments(){
		$picUrl ='';
		$responseofOne = array();
		$comm_res_id = Input::get('comm_id');
		$con_con_id =  Input::get('con_id');
		if($comm_res_id){
			$pb_ch = Channel::where('id', $con_con_id)->first();
			if($pb_ch->network_id==1)
			{
				$accessToken=(json_decode($pb_ch->auth_detail)->access_token);
				$commenturl="https://graph.facebook.com/".$comm_res_id."/comments?summary=true&access_token=";
				$commenturl_with_token = $commenturl.$accessToken ;
				$json = @file_get_contents($commenturl_with_token);
				if($json!=FALSE){
					$commentResponses=json_decode($json, true);
					if(count($commentResponses)>0){
						if(count($commentResponses['data'])>0){
							$responseofOne = array();
							foreach($commentResponses['data'] as $cm_key=>$commentInfo){
								$forPicOnly = "https://graph.facebook.com/v2.1/".$commentInfo['from']['id']."/?fields=picture,name&access_token=".$accessToken."";
								$jsonPic = @file_get_contents($forPicOnly);
								if($jsonPic!=FALSE){
									$picResponses=json_decode($jsonPic, true);
									$responseofOne[]=array(
										'channel_id' =>$con_con_id,
										'channel_id_name'=>$pb_ch->name,
										'commented_by_name' => $commentInfo['from']['name'],
										'commented_by_id' => $commentInfo['from']['id'],
										'comment_by_pic' => $picResponses['picture']['data']['url'],
										'commented_text' => $commentInfo['message'],
										'commented_time' => $commentInfo['created_time'],
										'comment_response' => $commentInfo['id'],
										'likes_on_reply' => $commentInfo['like_count']
									);
								}	
							}
						}
					}	
				}
				
			}
			
		}
		return Response::json($responseofOne);	
	}
	
	public function CompletePost(){
		$picUrl ='';
		$likeCount=0;
		$shareCount=0;
		$responseofOne = array();
		$seeFullInfoArr = array();
		$content_file = array();
		$con_ch_id = Input::get('con_id');
		$contentId_channelId=explode("*", $con_ch_id);
		$con_id = $contentId_channelId[0];
		if($con_ch_id!='0'){
		$ch_id = $contentId_channelId[1];
		}
		else{
		$ch_id = 0;}
		//var_dump($ch_id);exit;
		if($con_id>0){
			$content_resId = Publishstatus::where('content_id', $con_id)->where('status', "SUCCESS")->where('channel_id', $ch_id)->first();
			$seeFullInfo = ResponseComments::where('content_id', $con_id)->where('channel_id', $ch_id)->get();
			$content = Content::where('id', $con_id)->first();
			$pb_status = Publishstatus::where('content_id', $con_id)->first();
			$pb_ch = Channel::where('id', $ch_id)->first(); 
			$n_name = Network::where('id', $pb_ch->network_id)->first();
			$media_text=Attachment::where('content_id', $con_id)->get();
				if(sizeof($media_text)>0){
					foreach($media_text as $media){
						$content_file[]=array(
							'file_type'=>$media->type,
							'file_name'=>$media->filename
						);
					}	
				}
			$diffHour = $this->getTimeDiffrence($content_resId->updated_at);
			$responseofOne[]=array(
						'content_id' =>$content_resId->response,
						'content_text' => $content->content,
						'hours'=>$diffHour,
						'likes'=>$likeCount,
						'shares'=>$shareCount,
						'media_files' =>$content_file,
						'channel_id' =>$ch_id,
						'channel_id_name'=>$pb_ch->name,
						'channel_id_pic'=>$picUrl,
						'channel_id_name'=>$pb_ch->name,
						'commented_by_name' => '',
						'commented_by_id' => '',
						'comment_by_pic' => '',
						'commented_text' => '',
						'comment_response' => '',
						'n_name'=> strtolower($n_name->platform),
						
			);
			if($pb_ch->network_id==1)
			{
				$picUrl='';
				$likeCount=0;
				$shareCount=0;
				$accessToken=(json_decode($pb_ch->auth_detail)->access_token);
				$forPicOnly = "https://graph.facebook.com/v2.1/me/?fields=picture,name&access_token=".$accessToken."";
				$jsonPic = @file_get_contents($forPicOnly);
				if($jsonPic!=FALSE){
					$picResponses=json_decode($jsonPic, true);
					$picUrl = $picResponses['picture']['data']['url'];
				}
				$commenturl="https://graph.facebook.com/".$content_resId->response."/comments?summary=true&access_token=";
				$commenturl_with_token = $commenturl.$accessToken ;
				$json = @file_get_contents($commenturl_with_token);
				if($json!=FALSE){
					$commentResponses=json_decode($json, true);
					if(count($commentResponses)>0){
						if(count($commentResponses['data'])>0){
							$responseofOne = array();
							foreach($commentResponses['data'] as $cm_key=>$commentInfo){
								$forPicOnly = "https://graph.facebook.com/v2.1/".$commentInfo['from']['id']."/?fields=picture,name&access_token=".$accessToken."";
								$jsonPic = @file_get_contents($forPicOnly);
								if($jsonPic!=FALSE){
									//like
									$likesurl="https://graph.facebook.com/likes?id=".$content_resId->response."&access_token=";
									$likesurl_with_token = $likesurl.$accessToken ;
									$likecontext = @file_get_contents($likesurl_with_token);
									if($likecontext!=FALSE){
										$jsonlikes = file_get_contents($likesurl_with_token);
										$likesResponses=json_decode($jsonlikes, true);
										$totalcount=0;
										if($likesResponses['data']!=null && count($likesResponses)>=2){
											$totalcount=count($likesResponses['data']);
										}
										$likeCount+=$totalcount;
									}
									//
									//share
									$data=explode('_', $content_resId->response);
									$p_id=(count($data)>1)?$data[1]:$data[0];
									$sharedurl="https://graph.facebook.com/".$p_id."/sharedposts?access_token=";
				$sharedurlurl_with_token = $sharedurl.$accessToken ;
$context = '';
					$mod_date= filemtime($accessToken."sharedpostsfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($accessToken."sharedpostsfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
							$context = @file_get_contents($sharedurlurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
						$context = @file_get_contents($sharedurlurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
									if($context!=FALSE){
										$jsonshares = file_get_contents($sharedurlurl_with_token);
										$sharesResponses=json_decode($jsonshares, true);
										$shareCount+=count($sharesResponses['data']);
									}
									//
								$picResponses=json_decode($jsonPic, true);
									$responseofOne[]=array(
										'content_id' =>$content_resId->response,
										'content_text' => $content->content,
										'hours'=>$diffHour,
										'likes'=>$likeCount,
										'shares'=>$shareCount,
										'media_files' =>$content_file,
										'channel_id' =>$ch_id,
										'channel_id_name'=>$pb_ch->name,
										'channel_id_pic'=>$picUrl,
										'commented_by_name' => $commentInfo['from']['name'],
										'commented_by_id' => $commentInfo['from']['id'],
										'comment_by_pic' => $picResponses['picture']['data']['url'],
										'commented_text' => $commentInfo['message'],
										'comment_response' => $commentInfo['id'],
										'n_name'=> strtolower($n_name->platform),
										'likes_on_comment' => $commentInfo['like_count']
									);
								}	
							}
						}
						
					}//echo"<pre>";var_dump($responseofOne);exit;
					
				}
				
			}
			elseif($pb_ch->network_id==2)
			{
				$picUrl = '';
				$likeCount=0;
				$shareCount=0;
				$networkInfo = DB::table('network')->where('id', $pb_ch->network_id)->first();
				$CONSUMER_KEYs = $networkInfo->api_key;
				$CONSUMER_SECRETs = $networkInfo->secret_key;
				$accessToken = (json_decode($pb_ch->auth_detail)->access_token);
				$accesssecret = (json_decode($pb_ch->auth_detail)->access_secret);
				\Codebird\Codebird::setConsumerKey($CONSUMER_KEYs, $CONSUMER_SECRETs);
				$cb = \Codebird\Codebird::getInstance();
				$cb->setToken($accessToken, $accesssecret);
				$reply = $cb->statuses_show_ID('id=' . $content_resId->response);
				if ($reply->httpstatus == 200) {
					$likeCount+=$reply->favorite_count;
					$shareCount+=$reply->retweet_count;
					$picUrl = $reply->user->profile_image_url;
				}
				$rereply = (array)$cb->statuses_mentionsTimeline();
				$responseofOne = array();
								foreach ($rereply as $rep) {
									if (count((array)$rep) > 3 && $content_resId->response == $rep->in_reply_to_status_id_str) {
										$responseofOne[] = array(
											'content_id' =>$content_resId->response,
											'content_text' => $content->content,
											'hours'=>$diffHour,
											'likes'=>$likeCount,
											'shares'=>$shareCount,
											'media_files' =>$content_file,
											'channel_id' =>$ch_id,
											'channel_id_name'=>$pb_ch->name,
											'channel_id_pic'=>$picUrl,
											'commented_by_name' => $rep->user->name,
											'commented_by_id' => $rep->user->id,
											'comment_by_pic' => $rep->user->profile_image_url,
											'commented_text' => $rep->text,
											'comment_response' => $rep->id_str,
											'n_name'=> strtolower($n_name->platform),
											'reply_time' => $rep->created_at,
											'reply_favorite_count' => $rep->favorite_count,
											
										);
									}
								}
				//echo"<pre>";
				//var_dump($responseofOne);
				//exit;
			}
			elseif($pb_ch->network_id==3)
			{
				$picUrl ='';
				$likeCount=0;
				$shareCount=0;
				$urllinked="https://api.linkedin.com/v1/companies/2414183/updates/key=".$content_resId->response."?oauth2_access_token=";
				$accessToken=(json_decode($pb_ch->auth_detail)->access_token);
                $url_with_token = $urllinked.$accessToken."&format=json";
				$context=@file_get_contents($url_with_token);
				if($context!=FALSE){
							$linkedinresponse = json_decode(file_get_contents($url_with_token), true);
							$picUrl = $linkedinresponse['updateContent']["person"]["pictureUrl"];
							$likeCount+=$linkedinresponse["numLikes"];
							if(count($linkedinresponse["updateComments"]["values"])>0){
								$responseofOne = array();
								foreach($linkedinresponse["updateComments"]["values"] as $cm_key=>$commentInfo){
									$responseofOne[]=array(
										'content_id' =>$content_resId->response,
										'content_text' => $content->content,
										'hours'=>$diffHour,
										'likes'=>$likeCount,
										'shares'=>$shareCount,
										'media_files' =>$content_file,
										'channel_id' =>$ch_id,
										'channel_id_name'=>$pb_ch->name,
										'channel_id_pic'=>$picUrl,
										'commented_by_name' => $commentInfo['person']['firstName'].' '.$commentInfo['person']['lastName'],
										'commented_by_id' => $commentInfo['id'],
										'comment_by_pic' => $commentInfo['person']['pictureUrl'],
										'commented_text' => $commentInfo['comment'],
										'comment_response' => $commentInfo['id'],
										'n_name'=> strtolower($n_name->platform),
										'likes_on_comment' => 0
									);
								}
							}	
						}
						
				//echo"<pre>";
				//var_dump($responseofOne);
				//exit;
			}
			elseif($pb_ch->network_id==5)
			{
				$picUrl ='';
				$likeCount=0;
				$shareCount=0;
				$gnetwork = Network::where('id', 5)->first();
				$accessToken=$pb_ch->auth_detail;
				$client = new Google_Client();
				$client->setClientId($gnetwork->api_key);
				$client->setClientSecret($gnetwork->secret_key);
				$client->setScopes($gnetwork->scope);
				$client->setAccessType('offline');
				$client->setApprovalPrompt("auto");
				$client->setAccessToken($accessToken);
				$youtube = new Google_Service_YouTube($client);
				$context=@file_get_contents("https://www.googleapis.com/youtube/v3/videos?id=".$content_resId->response."&key=AIzaSyDwlUHX89WFsnePZN8UZ1ZmDOXFKvOLNyA&fields=items(id,snippet(channelId,title,categoryId),statistics)&part=snippet,statistics");
				if($context!=FALSE){
						$abc=json_decode($context,true);
						if(count($abc['items'])>0){
							$likeCount+=$abc['items'][0]['statistics']['likeCount'];
						}
				}
				if ($client->getAccessToken()) {
					try{
						// Call the YouTube Data API's commentThreads.list method to retrieve video comment threads.
						$videoComments = $youtube->commentThreads->listCommentThreads('snippet', array(
							'videoId' =>  $content_resId->response,// 'eRVRhRdaQSM',
							'textFormat' => 'plainText',
						));
						//$parentId = $videoCommentThreads[0]['id'];
						if(count($videoComments)>0){
							$responseofOne = array();
							foreach($videoComments as $cm_key=>$commentInfo){
								//echo"<pre>";var_dump($commentInfo['snippet']["topLevelComment"]["snippet"]["textDisplay"]);exit;
									$responseofOne[]=array(
												'content_id' =>$content_resId->response,
												'content_text' => $content->content,
												'hours'=>$diffHour,
												'likes'=>$likeCount,
												'shares'=>$shareCount,
												'media_files' =>$content_file,
												'channel_id' =>$ch_id,
												'channel_id_name'=>$pb_ch->name,
												'channel_id_pic'=>$picUrl,
												'commented_by_name' => $commentInfo['snippet']["topLevelComment"]['snippet']['authorDisplayName'],
												'commented_by_id' => $commentInfo['snippet']["topLevelComment"]['snippet']['authorGoogleplusProfileUrl'],
												'comment_by_pic' => $commentInfo['snippet']["topLevelComment"]['snippet']['authorProfileImageUrl'],
												'commented_text' => $commentInfo['snippet']["topLevelComment"]['snippet']['textDisplay'],
												'comment_response' => $commentInfo['snippet']["topLevelComment"]['id'],
												'n_name'=> strtolower($n_name->platform),
												'likes_on_comment' => $commentInfo['snippet']["topLevelComment"]['snippet']['likeCount'],
											);
								
							}
						}
						
					}
					catch (Google_Service_Exception $e) {
						var_dump($e);
					} 
					catch (Google_Exception $e) {
						var_dump($e);
					}
				}
				else {
				  // If the user hasn't authorized the app, initiate the OAuth flow
				  $state = mt_rand();
				  $client->setState($state);
				  $_SESSION['state'] = $state;

				  $authUrl = $client->createAuthUrl();
				 
				}
				return Response::json(array_reverse($responseofOne));				
				//var_dump($context);exit;
			}
			else
			{
				$responseofOne[]=array(
						'content_id' =>$content_resId->response,
						'content_text' => $content->content,
						'hours'=>$diffHour,
						'likes'=>$likeCount,
						'shares'=>$shareCount,
						'media_files' =>$content_file,
						'channel_id' =>$ch_id,
						'channel_id_name'=>$pb_ch->name,
						'channel_id_pic'=>$picUrl,
						'commented_by_name' => '',
						'commented_by_id' => '',
						'comment_by_pic' => '',
						'commented_text' => '',
						'comment_response' => '',
						'n_name'=> strtolower($n_name->platform),
				);
			}
			if(count($responseofOne)==0){
				$responseofOne[]=array(
						'content_id' =>$content_resId->response,
						'content_text' => $content->content,
						'hours'=>$diffHour,
						'likes'=>$likeCount,
						'shares'=>$shareCount,
						'media_files' =>$content_file,
						'channel_id' =>$ch_id,
						'channel_id_name'=>$pb_ch->name,
						'channel_id_pic'=>$picUrl,
						'commented_by_name' => '',
						'commented_by_id' => '',
						'comment_by_pic' => '',
						'commented_text' => '',
						'comment_response' => '',
						'n_name'=> strtolower($n_name->platform),
				);
			}
		}
		else{
			$responseofOne[]=array(
					'content_id' =>'',
					'content_text' => '',
					'hours'=>'UNKNOWN',
					'likes'=>0,
					'shares'=>0,
					'media_files' =>$content_file,
					'channel_id' => '',
					'channel_id_name'=>'',
					'channel_id_pic'=>'',
					'commented_by_name' => '',
					'commented_by_id' => '',
					'comment_by_pic' => '',
					'commented_text' => '',
					'comment_response' => '',
					'n_name'=> '',
			);
		}
		return Response::json($responseofOne);
	}
	
	public function getNetworkProject(){
		$AllprojectAllInfo = array();
		$channel_ids = array();
		$Contents_Texts = array();
		$manageProject= array();
		$ownProject= array_unique(Input::get('pro_ids'));
		
		$projects = array(
			'manageProject' => $manageProject,
			'ownProject' => $ownProject
		);
		$datas=Publishstatus::getResponseIds($projects);
		foreach($datas[0]["project_channel_ids"] as $pro_key=>$projects)
		{
			foreach($projects[key($projects)] as $cot_key=>$content){
			$f_like=0;$t_like=0;$l_like=0;$y_like=0;
			$f_share=0;$t_share=0;$l_share=0;$y_share=0;
			$f_comment=0;$t_comment=0;$l_comment=0;$y_comment=0;
			$f_network=0;$t_network=0;$l_network=0;$y_network=0;
			$f_channel_id=0;$t_channel_id=0;$l_channel_id=0;$y_channel_id=0;
				$contentId_response=explode("*", $content);
				$total_channelid[]=$contentId_response[0];
				if((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==1){
					$f_channel_id=$contentId_response[0];
					$responseObj = $contentId_response[1];
					$token=$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key];
					//Here get the likes
					$likesurl="https://graph.facebook.com/likes?id=".$responseObj."&access_token=";
					$accessToken=(json_decode($token)->access_token);
					$likesurl_with_token = $likesurl.$accessToken ;
					//$context = @file_get_contents($likesurl_with_token);
					
					$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."likewithjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."likewithjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
							$context = @file_get_contents($likesurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."likewithjson.txt", "w");
						$context = @file_get_contents($likesurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					
					if($context!=FALSE){
						$jsonlikes = file_get_contents($likesurl_with_token);
						$likesResponses=json_decode($jsonlikes, true);
						$userLikes = "";
						$likescount=2;
						$totalcount=0;
						if($likesResponses['data']!=null && count($likesResponses)>=2){
							$totalcount=count($likesResponses['data']);
						}
						$f_like+=$totalcount;
						$f_network=1;
					}
					//shared post
					$data=explode('_', $responseObj);
					$p_id=(count($data)>1)?$data[1]:$data[0];
					$sharedurl="https://graph.facebook.com/".$p_id."/sharedposts?access_token=";
				$sharedurlurl_with_token = $sharedurl.$accessToken ;
$context = '';
					$mod_date= filemtime($accessToken."sharedpostsfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($accessToken."sharedpostsfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
							$context = @file_get_contents($sharedurlurl_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($accessToken."sharedpostsfacebook.txt", "w");
						$context = @file_get_contents($sharedurlurl_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					if($context!=FALSE){
						$jsonshares = file_get_contents($sharedurlurl_with_token);
						$sharesResponses=json_decode($jsonshares, true);
						$f_share+=count($sharesResponses['data']);
						$f_network=1;
						$sharedhistory = array();
						if($sharesResponses['data']!=null && count($sharesResponses)>=2){
							foreach($sharesResponses['data'] as $sharesResponse){
								$sharedhistory[]= array(
									'story' => $sharesResponse['story'], 
								);
							}
						}
						else{
						 $sharedhistory[]= array(
								'story' =>'no any share your post.', 
							);
						}
					}
					//Here get the comments
					$commenturl="https://graph.facebook.com/".$responseObj."/comments?summary=true&access_token=";
					$commenturl_with_token = $commenturl.$accessToken ;

					
					$json = '';
					$mod_date= filemtime($this->filepath.$accessToken."graphfacebook.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."graphfacebook.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."graphfacebook.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
							fwrite($Userfile, $json);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."graphfacebook.txt", "r");
								$json = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."graphfacebook.txt", "w");
							$json = @file_get_contents($commenturl_with_token);
						fwrite($Userfile, $json);
						fclose($Userfile);
					}
					if($json!=FALSE){
						$commentResponses=json_decode($json, true);
						if(count($commentResponses)>1){
							$f_comment+=$commentResponses['summary']['total_count'];
						}
						$f_network=1;
					}
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$f_like,
					'share'=>$f_share,
					'comment'=>$f_comment,
					'channel'=>$f_channel_id
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==2){
					$t_channel_id=$contentId_response[0];
					$networkInfo = DB::table('network')->where('id', 2)->first();
					$CONSUMER_KEYs = $networkInfo->api_key;
					$CONSUMER_SECRETs = $networkInfo->secret_key;
					$token=$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key];
					if ($token != null) {
						$responseObj = $contentId_response[1];
						if ($responseObj != null) {
							$accessToken = (json_decode($token)->access_token);
							$accesssecret = (json_decode($token)->access_secret);
							\Codebird\Codebird::setConsumerKey($CONSUMER_KEYs, $CONSUMER_SECRETs);
							$cb = \Codebird\Codebird::getInstance();
							$cb->setToken($accessToken, $accesssecret);
							$reply = $cb->statuses_show_ID('id=' . $responseObj);
							$rereply = (array)$cb->statuses_mentionsTimeline();
							$tweetreply = array();
							foreach ($rereply as $rep) {
								if (count((array)$rep) > 3 && $responseObj == $rep->in_reply_to_status_id_str) {
									$tweetreply[] = array(
										'reply_text' => $rep->text,
										'twittes_id' => $rep->id_str,
										'twittes_screen_name' => $rep->user->name,
									);
									$ts_comment='Yes';
								}
							}
							if ($reply->httpstatus == 200) {
								$t_like+=$reply->favorite_count;
								$t_share+=$reply->retweet_count;
								$t_comment+=count($tweetreply);
								$t_network=2;
							}
						}
					}
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$t_like,
					'share'=>$t_share,
					'comment'=>$t_comment,
					'channel'=>$t_channel_id
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==3){
					$l_channel_id=$contentId_response[0];
					$responseObj = $contentId_response[1];
					$token=$datas[0]["project_network_ids"][$pro_key]['auth_detail'][$cot_key];
					$urllinked="https://api.linkedin.com/v1/companies/2414183/updates/key=".$responseObj."?oauth2_access_token=";
						$accessToken=(json_decode($token)->access_token);
						$url_with_token = $urllinked.$accessToken."&format=json";
						$context = '';
					$mod_date= filemtime($this->filepath.$accessToken."linkedinjson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$accessToken."linkedinjson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
							$context = @file_get_contents($url_with_token);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$accessToken."linkedinjson.txt", "w");
						$context = @file_get_contents($url_with_token);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					if($context!=FALSE){
						$linkedinresponse = json_decode(file_get_contents($url_with_token), true);
						$l_like+=$linkedinresponse["numLikes"];
						$l_comment+=$linkedinresponse["updateComments"]["_total"];
						$l_network=3;
					}
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$l_like,
					'share'=>$l_share,
					'comment'=>$l_comment,
					'channel'=>$l_channel_id
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==4){
					//var_dump('sumit4');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==5){
					$y_channel_id=$contentId_response[0];
					$responseObj = $contentId_response[1];
					$gooleURL = "https://www.googleapis.com/youtube/v3/videos?id=".$responseObj."&key=AIzaSyDwlUHX89WFsnePZN8UZ1ZmDOXFKvOLNyA&fields=items(id,snippet(channelId,title,categoryId),statistics)&part=snippet,statistics";
					
					$context = '';
					$mod_date= filemtime($this->filepath.$responseObj."googlejson.txt");					
					$now_date=strtotime('+1 hour');
					if(file_exists($this->filepath.$responseObj."googlejson.txt")) {
						if($now_date>$mod_date) {
							$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
							$context = @file_get_contents($gooleURL);
							fwrite($Userfile, $context);
							fclose($Userfile);
						} else {
								$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "r");
								$context = file_get_contents($Userfile);
								fclose($Userfile);
						}
					} else {
						$Userfile = fopen($this->filepath.$responseObj."googlejson.txt", "w");
						$context = @file_get_contents($gooleURL);
						fwrite($Userfile, $context);
						fclose($Userfile);
					}
					if($context!=FALSE){
						$abc=json_decode($context,true);
						if(count($abc['items'])>0){
							$y_like+=$abc['items'][0]['statistics']['likeCount'];
							$y_comment+=$abc['items'][0]['statistics']['commentCount'];
							$y_network=5;
						}
					}
					$conntent_result[]=array(
					'content_id'=>$contentId_response[2],
					'like'=>$y_like,
					'share'=>$y_share,
					'comment'=>$y_comment,
					'channel'=>$y_channel_id
					);
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==6){
					//var_dump('sumit6');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==7){
					//var_dump('sumit7');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==8){
					//var_dump('sumit8');
				}
				elseif((int)$datas[0]["project_network_ids"][$pro_key][key($projects)][$cot_key]==9){
					//var_dump('sumit9');
				}
				
			}
			
			foreach($conntent_result as $con_key=>$singlecon){
				$content_text=Content::getContentById($singlecon['content_id']);
				$media_text=Attachment::where('content_id', $singlecon['content_id'])->get();
				if(sizeof($media_text)>0){
					foreach($media_text as $media){
						if($media->type=='IMAGE'){
							$content_file[]=$media->filename;
						}else{
							$content_file[]='';
						}
					}
					$total_content_text[$singlecon['content_id']]=array(
						'text'=>$content_text->content,
						'time'=>$content_text->created_at,
						'file_name'=>$content_file,
					);
				}else{
					$total_content_text[$singlecon['content_id']]=array(
						'text'=>$content_text->content,
						'time'=>$content_text->created_at,
						'file_name'=>array(),
					);
				}	
			}
			$total_channelid=array_unique($total_channelid);
			foreach($total_channelid as $ch_key=>$channel){
				$channel_info=Channel::where('id',$channel)->first();
				if($channel_info){
					$netInfo = Network::where('id', $channel_info->network_id)->first();
					$total_channel_info[$channel]=array(
					'name'=>$channel_info->name,
					'network'=>strtolower($netInfo->platform),
					);
				}	
			}
			
			$AllprojectAllInfo[key($projects)] = array(
				'total_content_text' => $total_content_text,
				'total_channel_info' => $total_channel_info,
				'content_result' => $conntent_result
			
			);
			$total_content_text = array();
			$total_channel_info = array();
			$conntent_result = array();
			
		}
		//echo"<pre>";
		//var_dump($AllprojectAllInfo);exit;
		return Response::json($AllprojectAllInfo);
	}

	public function postReply(){
		/*$responseObj = '644775740832133120';
		$networkInfo = DB::table('network')->where('id', 2)->first();
		$CONSUMER_KEYs = $networkInfo->api_key;
		$CONSUMER_SECRETs = $networkInfo->secret_key;
		$tokenz=DB::table('channel')->where('id', 541)->first();
		$token=$tokenz->auth_detail;
		$accessToken = (json_decode($token)->access_token);
								$accesssecret = (json_decode($token)->access_secret);
								\Codebird\Codebird::setConsumerKey($CONSUMER_KEYs, $CONSUMER_SECRETs);
								$cb = \Codebird\Codebird::getInstance();
								$cb->setToken($accessToken, $accesssecret);
		$params = array(
            'status' => '@sumitdnit Test reply!',
            'in_reply_to_status_id' => '644775740832133120',
            );
		$reply = $cb->statuses_update($params);*/
		
		/*// facebook rply
		$p_id='1569585423364165';
		$tokenz=DB::table('channel')->where('id', 690)->first();
		$token=$tokenz->auth_detail;
		$accessToken = 'CAACEdEose0cBABSzVZBc6yU9RHVlsqmhqyEQ59BgZByFTTotdx0L1dFGczoYjks9MFrgcwmhmG1vZC4B8ZCNyFGeUYKx7rkUGFPcml34jUBzf02kGdv76vnuQJfIzL0thPWvfUANEm6SjD78E7ZCiN2tz9ZAsp7sM4EwVvWvnM8JMq8J5Te52BliNwiw5JbbQzWUl7hh8lpgZDZD';//(json_decode($token)->access_token);
		//https://graph.facebook.com/1569585423364165/comments?access_token=CAACEdEose0cBABSzVZBc6yU9RHVlsqmhqyEQ59BgZByFTTotdx0L1dFGczoYjks9MFrgcwmhmG1vZC4B8ZCNyFGeUYKx7rkUGFPcml34jUBzf02kGdv76vnuQJfIzL0thPWvfUANEm6SjD78E7ZCiN2tz9ZAsp7sM4EwVvWvnM8JMq8J5Te52BliNwiw5JbbQzWUl7hh8lpgZDZD&message=Raaz
		$sharedurl="https://graph.facebook.com/".$p_id."/comments?";
		$likesurl_with_token = $sharedurl.$accessToken ;
		$postdata = http_build_query(
			array(
				'message' => 'some new Raaz1',
				'access_token' => 'CAACEdEose0cBAGL7m1MZCoDBXNW4OnNOZCPxY3cL0wSuRxZCaI48IyOCnprtmhVQUtODMXXLEByZC71PTxECpYoSxZBMV6d5DRHZBLhxrCsrGiarZAxJEWMUP1cGNLEgYreLgwRMxU1YHFoIGtlsIk36c25968p9Cn096LfT3fBBJ1wxN4K4Fdaxq5PLvK53SOt2ExCO2bzCllzi7g8QG8v'
			)
		);
		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'content' => $postdata
			)
		);
		
		$p_data = stream_context_create($opts);
		$context = @file_get_contents($likesurl_with_token, false, $p_data);
		echo"<pre>";var_dump($context);exit;*/
		
		$client = new Google_Client();
		$client->setClientId('275273931928-b62ipblk98mn2epl705t57ve93codmsh.apps.googleusercontent.com');
		$client->setClientSecret('Iu89ZZ-dqwJazy83At_hFFaO');
		$client->setScopes('https://www.googleapis.com/auth/youtube.force-ssl','https://www.googleapis.com/auth/youtube','https://www.googleapis.com/auth/plus.login');
		$client->setAccessType('offline');
		$client->setApprovalPrompt("auto");
		$client->setAccessToken('
{"access_token":"ya29.hgJGk6yoKfeTfLvxocbijcS4B_oMQho76Bt3nw45rvfb-DGCXkSVh_NKJC1bZk2xBT8j","token_type":"Bearer","expires_in":3600,"id_token":"eyJhbGciOiJSUzI1NiIsImtpZCI6ImE0MTYzNjE5NDIzZGNkM2E3MzYxYWNmMmE2NDFiZjZmN2M5ZTQ4OGEifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXRfaGFzaCI6Il9tbEZsSDNTbFpNMDFlamt2VVdKd2ciLCJhdWQiOiIyNzUyNzM5MzE5MjgtYjYyaXBibGs5OG1uMmVwbDcwNXQ1N3ZlOTNjb2Rtc2guYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMTI4Nzg2ODQzMDE4ODY1ODg4OTAiLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwiYXpwIjoiMjc1MjczOTMxOTI4LWI2MmlwYmxrOThtbjJlcGw3MDV0NTd2ZTkzY29kbXNoLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiZW1haWwiOiJhYmhpbmF2dW1hbmcxOEBnbWFpbC5jb20iLCJpYXQiOjE0NTUyNjkzMjYsImV4cCI6MTQ1NTI3MjkyNn0.tU7-PMg3TWX76MNXtVe8LYAgqk3S5xze6OySE7ypOH1d81yGATfzYwq0gyH_PtmqH0lQ9tT6G7lVyUTSBM-rN_TP-V0saRsV0PzVyUvAJUsgC2nU7jB5fTRnPLkdHPxhzpLIlj6m2Wbf109qvuRMSYJwsSkGrm6ssTVf2CDKoVKSkIRAYR5jxJeMU4gMaRDJ9Hp6b0E1Lp0Zk8fq_r_mNK8msc0nrHGr0SeH4a_Sc1j4IOcTLzwoY1wWip-CmUkt7YwSAbxEjR8-bfeOuM0B9m90anKeGwLtWIgmGg_oItSH4_gMYwzsD-oxfEhJS5kkV_qGC1oexY7XncjbwWs16g","refresh_token":"1\/DCLT_8hB_E2l6PUi5WsbQSTKDQI_sm9XL_KGvgtG0wZIgOrJDtdun6zK6XiATCKT","created":1455269325}');
		$youtube = new Google_Service_YouTube($client);
		if ($client->getAccessToken()) {
			try{
				$commentSnippet = new Google_Service_YouTube_CommentSnippet();
				$commentSnippet->setTextOriginal('Hello Check!');
				
				$topLevelComment = new Google_Service_YouTube_Comment();
				$topLevelComment->setSnippet($commentSnippet);
				
				$commentThreadSnippet = new Google_Service_YouTube_CommentThreadSnippet();
				$commentThreadSnippet->setVideoId('kJn921Wq7L4');
				$commentThreadSnippet->setTopLevelComment($topLevelComment);
				
				$commentThread = new Google_Service_YouTube_CommentThread();
				$commentThread->setSnippet($commentThreadSnippet);
				
				$videoCommentInsertResponse = $youtube->commentThreads->insert('snippet', $commentThread);
			}
			catch (Google_Service_Exception $e) {
				var_dump($e);exit;
			} 
			catch (Google_Exception $e) {
				var_dump($e);exit;
			}

		}
		else {
		  // If the user hasn't authorized the app, initiate the OAuth flow
		  var_dump('Hello!');exit;
		  $state = mt_rand();
		  $client->setState($state);
		  $_SESSION['state'] = $state;

		  $authUrl = $client->createAuthUrl();
		 
		}
		
		
		
	}
	
	public function postReplyOnComment(){
		//$p_id='1569585423364165';
		//$user_id = $this->user->id;
		$comm_res_id = Input::get('comm_id');
		$con_ch_id =  Input::get('con_id');
		$reply_text =  Input::get('reply_text');
		//var_dump($comm_res_id);
		//var_dump($con_ch_id);
		//var_dump($reply_text);exit;
		$p_id = $comm_res_id;
		$tokenz=DB::table('channel')->where('id', 690)->first();
		$token=$tokenz->auth_detail;
		$accessToken = 'CAACEdEose0cBAG8DmyAJZBqJzjvLTibHcfqicrNs4iJIdYnvqyo1ECYar9pkvVOSF86DpSZB2inXWNPZAl8oOP6JZBajHh1BFo8S75oZC0ZBZCSHBcmw0b8GYxVeN33gZCXgNFaXZAYZBt6ujU5kjo4tIPZCCOVKALNWyhTpKbknXYZB8LuLUMMM30nWTZCEBX0ERjLqaGVA4FMKaNAZDZD';//(json_decode($token)->access_token);
		$sharedurl="https://graph.facebook.com/".$p_id."/comments?";
		$likesurl_with_token = $sharedurl.$accessToken ;
		$postdata = http_build_query(
			array(
				'message' => $reply_text,
				'access_token' => 'CAACEdEose0cBAG8DmyAJZBqJzjvLTibHcfqicrNs4iJIdYnvqyo1ECYar9pkvVOSF86DpSZB2inXWNPZAl8oOP6JZBajHh1BFo8S75oZC0ZBZCSHBcmw0b8GYxVeN33gZCXgNFaXZAYZBt6ujU5kjo4tIPZCCOVKALNWyhTpKbknXYZB8LuLUMMM30nWTZCEBX0ERjLqaGVA4FMKaNAZDZD'
			)
		);
		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'content' => $postdata
			)
		);
		
		$p_data = stream_context_create($opts);
		$context = @file_get_contents($likesurl_with_token, false, $p_data);
		echo"<pre>";var_dump($context);exit;
	}
}