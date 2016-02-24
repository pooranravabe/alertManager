<?php

$networks = Network::lists('platform', 'id');
$consumers = array();

foreach($networks as $network_id => $platform){
	if($platform == 'FACEBOOK_PAGE') continue;

	if(in_array($platform, array('FACEBOOK', 'YOUTUBE'))){
		$consumers[ucfirst(strtolower($platform))] = Network::getCredentials($platform,'withscope');
		continue;
	}

	$consumers[ucfirst(strtolower($platform))] = Network::getCredentials($platform);
}
$return = array(
  /*
    |--------------------------------------------------------------------------
    | oAuth Config
    |--------------------------------------------------------------------------
   */

  /**
   * Storage
   */
  'storage' => 'Session',
  /**
   * Consumers
   */
  'consumers' => $consumers
);

return $return;
