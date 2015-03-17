<?php
namespace SlackHookFramework;

use Psr\Log\LogLevel;

define ('URL_CHANNELS_INFO', "https://slack.com/api/channels.info");
define ('URL_GROUPS_LIST',   "https://slack.com/api/groups.list");

class Configuration {
  public $token;
  public $slack_webhook_url;
  public $api_channels_info_url;
  public $api_groups_list_url;
  public $slack_api_token;
  public $log_level;
  public $default_channel;
  public $log_dir;
  public $db_dir;
  public $custom_cmds;

  public function __construct () {
    $this->token = null;
    $this->slack_webhook_url = null;
    $this->api_channels_info_url = URL_CHANNELS_INFO;
    $this->api_groups_list_url = URL_GROUPS_LIST;
    $this->slack_api_token = null;
    $this->log_level = LogLevel::DEBUG;
    $this->default_channel = null;
    $this->log_dir = "../../logs";
    $this->db_dir = "../../db";
    $this->custom_cmds = "../../custom_cmds.json";
  }
}

