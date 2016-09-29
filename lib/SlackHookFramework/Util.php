<?php

namespace SlackHookFramework;

use Katzgrau\KLogger\Logger;

/**
 * Class to store utility methods.
 *
 * @author Luis Augusto Peña Pereira <luis at digitalicagroup dot com>
 *        
 */
class Util {
	/**
	 * Function to post a payload to a url using cURL.
	 *
	 * @param string $url        	
	 * @param string $payload        	
	 * @param string $contentType        	
	 * @return mixed the result of the curl execution.
	 */
	public static function post($url, $payload, $contentType = 'Content-Type: application/json') {
		// TODO move constants to global configuration file
		$ch = curl_init ( $url );
		curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
				$contentType,
				'Content-Length: ' . strlen ( $payload ) 
		) );
		$result = curl_exec ( $ch );
		return $result;
	}
	
	/**
	 * Locates (using slack api) the channel name of a given channel id.
	 *
	 * @param \SlackHookFramework\Configuration $config        	
	 * @param string $channelId
	 *        	slack channel id to be found.
	 * @return string
	 */
	public static function getChannelName($config, $channelId) {
		// TODO move constants to global configuration file
		$log = new Logger ( $config->log_dir, $config->log_level );
		$channel = '';
		$api_channels_info_url = $config->api_channels_info_url;
		$api_groups_list_url = $config->api_groups_list_url;
		$slack_api_token = $config->slack_api_token;
		// Querying channels info service first
		$payload = array (
				"token" => $slack_api_token,
				"channel" => $channelId 
		);
		$log->debug ( "Util: going to invoke channels.info: $api_channels_info_url with payload: " . http_build_query ( $payload ) );
		
		$result = self::post ( $api_channels_info_url, http_build_query ( $payload ), 'multipart/form-data' );
		if (! $result) {
			$log->error ( "Util: Error sending: " . http_build_query ( $payload ) . " to channels info service: $api_channels_info_url" );
		}
		$result = json_decode ( $result, true );
		if ($result ["ok"]) {
			// Channel found!
			$channel = $result ["channel"] ["name"];
			$log->debug ( "Util: channel found!: " . $channel );
		} else {
			// Querying groups list service
			$log->debug ( "Util: going to invoke groups.list: $api_groups_list_url with payload: " . http_build_query ( $payload ) );
			$payload = array (
					"token" => $slack_api_token 
			);
			$result = self::post ( $api_groups_list_url, http_build_query ( $payload ), 'multipart/form-data' );
			if (! $result) {
				$log->error ( "Util: Error sending: " . http_build_query ( $payload ) . " to groups list service: $api_channels_info_url" );
			}
			$result = json_decode ( $result, true );
			if ($result ["ok"]) {
				// look for group
				foreach ( $result ["groups"] as $group ) {
					if (strcmp ( $group ["id"], $channelId ) == 0) {
						$channel = $group ["name"];
						$log->debug ( "Util: group found!: " . $channel );
						break;
					}
				}
			}
		}
		return "#" . $channel;
	}
	
	/**
	 * Returns a \SlackHookFramework\SlackResultAttachmentField instance with the given values.
	 *
	 * @param string $title        	
	 * @param string $value        	
	 * @param boolean $short
	 *        	If true, returns a "short" field. Slack can render two short fields
	 *        	in two columns, or a "long" field ($short = false) in a single column.
	 * @return \SlackHookFramework\SlackResultAttachmentField
	 */
	public static function createField($title, $value, $short = true) {
		$field = new SlackResultAttachmentField ();
		$field->setTitle ( $title );
		$field->setValue ( $value );
		$field->setShort ( $short );
		return $field;
	}
}
