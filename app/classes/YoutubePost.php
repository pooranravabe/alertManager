<?php

// Call set_include_path() as needed to point to your client library.
require_once 'Google/Client.php';
require_once 'Google/Service/YouTube.php';
require_once 'Google/Http/MediaFileUpload.php';

class YoutubePost {

  public static function publishPost($publish_data) {
    $token_data = json_decode($publish_data['auth_detail'], true);
    $client = new Google_Client();
    $client->setClientId($publish_data['api_key']);
    $client->setClientSecret($publish_data['secret_key']);
    $client->setScopes(explode(',', $publish_data['scope']));
    $client->setApplicationName('HBRAVABE');
    $client->setAccessType('offline');
    $client->setApprovalPrompt("auto");
    $client->setAccessToken($publish_data['auth_detail']);
    $youtube = new Google_Service_YouTube($client);
    if ($client->getAccessToken()) {
      try{
        $userInfo =null;
        $userInfoService = new Google_Service_Oauth2($client);
        $userInfo = $userInfoService->userinfo->get();
      }catch(Exception $e){
        $response = array(
          'code' => $e->getCode(),
          'response' => $e->getMessage(),
          'status' => 'FAILED'
          );
      }
      if ($userInfo == null) {
        return $response;
      } 
      try {
        // $data = $client->revokeToken($token_data['access_token']);
        if ($client->isAccessTokenExpired()) {
         $refreshToken = $token_data['refresh_token'];
                    //Here's where the magical refresh_token comes into play
         $client->refreshToken($refreshToken);
         $new_token = $client->getAccessToken();
         $find = Channel::where('id', '=', $publish_data['channel_id'])->first();
         $find->auth_detail = $new_token;
         $find->save();
       }
       if (!empty($publish_data['video_url'])) {
        $videoPath = public_path() . '/uploads/'. $publish_data['video_url'];
        $snippet = new Google_Service_YouTube_VideoSnippet();
        $snippet->setTitle(str_replace("&nbsp;", '', $publish_data['title']));
        $snippet->setDescription(str_replace("&nbsp;", '', strip_tags($publish_data['content'])));
        $snippet->setCategoryId("22");
        $status = new Google_Service_YouTube_VideoStatus();
        $status->privacyStatus = "public";
        $chunkSizeBytes = 1 * 1024 * 1024;
        $client->setDefer(true);
        $video = new Google_Service_YouTube_Video();
        $video->setSnippet($snippet);
        $video->setStatus($status);
// Create a request for the API's videos.insert method to create and upload the video.
        $insertRequest = $youtube->videos->insert("status,snippet", $video);
        $media = new Google_Http_MediaFileUpload(
          $client, $insertRequest, 'video/*', null, true, $chunkSizeBytes
          );
        $media->setFileSize(filesize($videoPath));
// Read the media file and upload it chunk by chunk.
        $status = false;
        $handle = fopen($videoPath, "rb");
        while (!$status && !feof($handle)) {
          $chunk = fread($handle, $chunkSizeBytes);
          $status = $media->nextChunk($chunk);
        }
        fclose($handle);
        if ($status->status['uploadStatus'] == 'uploaded') {
          $response = array(
            'response' => $status->snippet['channelId'],
            'status' => 'SUCCESS'
            );
        }else{
          $response = array(
            'response' => $status->snippet['channelId'],
            'status' => 'SUCCESS'
            );
        }
        $client->setDefer(true);
      }else{
        $response = array(
          'response' => 'No video to upload.',
          'status' => 'FAILED'
          );
      }
    } catch (Google_ServiceException $e) {
      $response = array(
        'response' => $e->getMessage(),
        'status' => 'FAILED'
        );
    } catch (Google_Exception $e) {
      $response = array(
        'code' => getCode(),
        'response' => $e->getMessage(),
        'status' => 'FAILED'
        );
    }
    return $response;
  }


}

}
