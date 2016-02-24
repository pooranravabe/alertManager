<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PublishRequest extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:PublishRequest';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->processPost($this->option('content_id'));
	}

	private function processPost($content_id){
		$content_data = Publishstatus::where('content_id', '=', $content_id)
									  ->where('status', '=', 'PENDING')->get();
		if ($content_data->count() >= 1) {
			foreach ($content_data as $publish_info) {
				$approvals = Approvals::where('content_id', $publish_info->content_id)->whereIn('approvel_status', ['0','2'])->get();
				if($approvals->count() >= 1){
					continue;
				}
				$image_urls = array();
				$video_url = null;
				$caption = '';
				$attachments = $publish_info->content->attachment()->get();
				if($attachments){
					if(isset($attachments[0])){
						if(isset($attachments[0]['caption'])){
							$caption =$attachments[0]['caption'];
						}
					}
					foreach ($attachments as $attachment) {
						switch ($attachment->type) {
							case 'IMAGE':
							$image_urls[]= $attachment->filename;
							break;
							case 'VIDEO':
							$video_url = $attachment->filename;
							break;
						}
					}

				}
				$image_urls= implode(',',$image_urls);
				$publish_data = array(
					'image_url' => $image_urls,
					'video_url' => $video_url,
					'user_id' => $publish_info->channel->user_id,
					'authour_id' => $publish_info->content->author_id,
					'title' => $publish_info->content->title,
					'caption' => $caption,
					'project_title'=> $publish_info->content->project->title,
					'url' => $publish_info->content->url,
					'content' => $publish_info->content->content,
					'project_id' => $publish_info->content->project_id,
					'content_id' => $publish_info->content_id,
					'network' => $publish_info->channel->network->platform,
					'api_key' => $publish_info->channel->network->api_key,
					'secret_key' => $publish_info->channel->network->secret_key,
					'scope' => $publish_info->channel->network->scope,
					'channel_id' => $publish_info->channel_id,
					'auth_detail' => $publish_info->channel->auth_detail,
					'remote_id' => $publish_info->channel->remote_id,
					'name' => $publish_info->channel->name
					);
				 $this->sendRequest($publish_data);
		 }
		} else {
			$this->info('Records not found');
		}
	}

	function sendRequest($publish_data) {
		switch ($publish_data['network']) {
			case 'TWITTER':
			$response = TwitterPost::publishPost($publish_data);
			break;
			case 'FACEBOOK':
			$response = FacebookPost::publishPost($publish_data);
			break;
			case 'FACEBOOK_PAGE':
			$response = FacebookPost::publishPost($publish_data);
			break;
			case 'LINKEDIN':
			$response = LinkedInPost::publishPost($publish_data);
			break;
			case 'YOUTUBE':
			$response = YoutubePost::publishPost($publish_data);
			break;
			case 'FLICKR':
			$response = FlickrPost::publishPost($publish_data);
			break;
			case 'WORDPRESS':
			$response = WordpressPost::publishPost($publish_data);
			break;
			case 'LINKEDIN_COMPANY':
			$response = LinkedInCompanyPost::publishPost($publish_data);
			break;
			case 'TUMBLR':
			$response = TumblrPost::publishPost($publish_data);
			break;
		}
		$response_data = Publishstatus::where('content_id', '=', $publish_data['content_id'])
		->where('channel_id', '=', $publish_data['channel_id'])->first();
		$response_data->status = $response['status'];
		$response_data->response = strip_tags($response['response']);
		$response_data->save();
		$pending_failed_status = Publishstatus::where('content_id', $publish_data['content_id'])->whereIn('status', array('PENDING', 'FAILED'))->get();
		$content = Content::where('id', $publish_data['content_id'])->first();
		if ($pending_failed_status->count()) {
			$content->status = 'PENDING';
		}else{
			$content->status = 'PUBLISHED';
		}
		$content->save();
		if(!empty($response['code'])){
			if($response['code'] == 190 || $response['code'] == 89 || $response['code'] == 401 || $response['code']== 403 || $response['code']== 404){
				Channel::markReConnect($publish_data['channel_id']);
			}
		}
		if($response['status']=="FAILED"){
			Alerts::insertAlerts(array(
				"content_id" => $publish_data['content_id'],
				"authour_id" => $publish_data['authour_id'],
				"project_id" => $publish_data['project_id'],
				"message" => Lang::get('errors.publish_failed_notification', ['network_name' => $publish_data['network'],'content_id' => $publish_data['content_id']])
				));
			if($publish_data['authour_id'] != $publish_data['user_id']){
				Alerts::setAlerts(array(
				"content_id" => $publish_data['content_id'],
				"user_id" => $publish_data['user_id'],
				"project_id" => $publish_data['project_id'],
				"message" => Lang::get('errors.publish_failed_notification', ['network_name' => $publish_data['network'],'content_id' => $publish_data['content_id']])
				));
			}
			$approvals = Approvals::where('content_id', $publish_data['content_id'])->get();
			foreach ($approvals as $approval) {
				if($publish_data['user_id'] == $approval->user_id){
					continue;
				}
				Alerts::setAlerts(array(
				"content_id" => $publish_data['content_id'],
				"user_id" => $approval->user_id,
				"project_id" => $publish_data['project_id'],
				"message" => Lang::get('errors.publish_failed_notification', ['network_name' => $publish_data['network'],'content_id' => $publish_data['content_id']])
				));
			}
		}
		
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('content_id', InputArgument::OPTIONAL, 'Content id must be'),
			);
		// return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('content_id', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
			);
		//return [];
	}

}
