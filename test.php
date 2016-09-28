<?php
require_once 'vendor/autoload.php';

$config = new SlackHookFramework\Configuration ();
$config->token = "TTTTTTTTTTTTTTTTT";
$config->slack_webhook_url = "https://hooks.slack.com/services/XXX/YYY/zzzzzzzzzzzz";
$config->slack_api_token = "xoxp-XXXXXX";
$config->log_dir = __DIR__."/logs";
$config->db_dir = __DIR__."/db";
$config->custom_cmds = __DIR__."/custom_cmds.json";

$post = array ();
$post ['text'] = "hello first second thrid";
$post ['user_name'] = "frameworkTest";
$post ['channel_id'] = 'testing';
// if (! SlackHookFramework\Validator::validate ( $_POST, $config )) {
// die ();
// }

$command = SlackHookFramework\CommandFactory::create ( $post, $config );
$result = $command->execute ();
print ($result->toJsonPretty ()) ;
// $command->post ();

?>