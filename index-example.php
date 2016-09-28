<?php
require_once 'vendor/autoload.php';

use Psr\Log\LogLevel;

/**
 * This is the entry point for your redmine-command slack integration.
 * It handles the configuration parameters and invokes the command
 * factory parsing and execution of commands.
 * This file should be placed at the same level of your "vendor" folder.
 */

$config = new SlackHookFramework\Configuration ();

/**
 * token sent by slack (from your "Slash Commands" integration).
 */
$config->token = "vuLKJlkjdsflkjLKJLKJlkjd";

/**
 * URL of the Incoming WebHook slack integration.
 */
$config->slack_webhook_url = "https://hooks.slack.com/services/LKJDFKLJFD/DFDFSFDDSFDS/sdlfkjdlkfjLKJLKJKLJO";

/**
 * Slack API authentication token for your team.
 */
$config->slack_api_token = "xoxp-98475983759834-38475984579843-34985793845";

/**
 * Log level threshold.
 * The default is DEBUG.
 * If you are done testing or installing in production environment,
 * uncomment this line.
 */
// $config->log_level = LogLevel::WARNING;

/**
 * logs folder, make sure the invoker(*) have write permission.
 */
$config->log_dir = __DIR__."/logs";

/**
 * Database folder, used by some commands to store user related temporal information.
 * Make sure the invoker(*) have write permission.
 */
$config->db_dir = __DIR__."/db";

/**
 * Custom commands definition. Use this file if you wish to add new commands to be
 * recognized by the framework.
 */
$config->custom_cmds = __DIR__."/custom_cmds.json";

/**
 * This is to prevent the entry point to be called outside slack.
 * It will validate the slack token in the request.
 */
if (! SlackHookFramework\Validator::validate ( $_POST, $config )) {
	die ();
}

/**
 * Entry point execution.
 */
$command = SlackHookFramework\CommandFactory::create ( $_POST, $config );
$command->execute ();
$command->post ();

/**
 * (*) Give permissions to your logs/ and db/ folder to your web server process.
 * If you are using apache under linux, it is usually www-data:
 * sudo chown -R :www-data logs/
 * sudo chown -R :www-data db/
 * sudo chmod g+w logs/
 * sudo chmod g+w db/
 */