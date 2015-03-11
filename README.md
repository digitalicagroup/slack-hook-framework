# Slack Hook Framework

A simple framework to build your own command processor for Slack.
It can be extended in two ways:
* Creating another project and using composer to install the framework as a library.
 * See [slack-bot](https://github.com/digitalicagroup/slack-bot) for example.
* Install the framework in stand alone mode (see Install).

Uses
* One Slack "Slash Commands" and one "Incoming WebHooks" integration (see Install).
* [KLogger](https://github.com/katzgrau/KLogger)

How does it work? (stand alone)
* It installs as a PHP application on your web server.
* Through a "Slash Commands" Slack integration, it receives requests.
* Posts the results to an "Incoming WebHooks" Slack integration in the originator's channel or private group (yeah, private group!).

## Current Features
* Commands supported:
 * help: Shows help info about other commands. It gathers information from the commands_definition.json file.
 * hello: Example command that shows what can be done with the framework.
 
## Requirements

* PHP >= 5.4 with cURL extension,
* Slack integrations (see install).

## Install (composer)
Just add the dependency for slack-hook-framework or run:
```bash
$ php composer.phar require digitalicagroup/slack-hook-framework:~0.1
```

## Install (stand alone)
If you want to install the framework in stand alone mode and begin working directly over it, follow this steps:

### On Slack

* Create a new "Slash Commands" integration with the following data:
 * Command: /test (or whatever you like)
 * URL: the URL pointing to the index.php of your slack-bot install
 * Method: POST
 * Token: copy this token, we'll need it later.

* Create a new "Incoming WebHooks" slack integration:
 * Post to Channel: Pick one, but this will be ignored by slack-bot.
 * Webhook URL: copy this URL, we'll need it later.
 * Descriptive Label, Customize Name, Customize Icon: whatever you like.

* Go to [Slack API](https://api.slack.com/) and copy the authentication token for your team.

### On your web server

Install [composer](http://getcomposer.org/download/) in a folder of your preference (should be accessible from your web server) then run:
```bash
$ php composer.phar require digitalicagroup/slack-hook-framework:~0.1
$ cp vendor/digitalicagroup/slack-hook-framework/index-example.php .
```

Edit index-example.php and add the following configuration parameters:
```php
/**
 * token sent by slack (from your "Slash Commands" integration).
 */
$config->token =              "vuLKJlkjdsflkjLKJLKJlkjd";

/**
 * URL of the Incoming WebHook slack integration.
 */ 
$config->slack_webhook_url =  "https://hooks.slack.com/services/LKJDFKLJFD/DFDFSFDDSFDS/sdlfkjdlkfjLKJLKJKLJO";

/**
 * Slack API authentication token for your team.
 */
$config->slack_api_token =    "xoxp-98475983759834-38475984579843-34985793845";

/**
 * Log level threshold. The default is DEBUG.
 * If you are done testing or installing in production environment,
 * uncomment this line.
 */
//$config->log_level =           LogLevel::WARNING;

/**
 * logs folder, make sure the invoker have write permission.
 */
$config->log_dir =            "/srv/api/slack-hook-framework/logs";

/**
 * Database folder, used by some commands to store user related temporal information.
 * Make sure the invoker have write permission.
 */
$config->db_dir = "/srv/api/slack-framework/db";
```

Give permissions to your logs/ and db/ folder to your web server process. If you are using apache under linux, it is usually www-data:
```bash
$ sudo chown -R :www-data logs/
$ sudo chown -R :www-data db/
$ sudo chmod g+w logs/
$ sudo chmod g+w db/
```

Go to slack and type `/test help`.

## Adding more Commands.

* If You wish to add more commands, the beginner's way is to go to the folder vendor/digitalicagroup/slack-hook-framework/lib/SlackHookFramework and add new commands there.
* Check the contents of CmdHello.php to see what a command can do.
* Then add a new entry to the commands_definition.json file.
* run composer update (php composer.phar update).

## Troubleshooting

This is a list of common errors:
* "I see some errors about permissions in the apache error log".
 * The process running slack-bot (usually the web server) needs write permissions to the folder configured in you $config->log_dir parameter.
 * For example, if you are running apache, that folder group must be assigned to www-data and its write permission for groups must be turned on.
  * change to your slack-hook-framework dir
  * chown -R :www-data logs/
  * chmod -R g+w logs/
* "I followed the steps and nothing happens, nothing in web server error log and nothing in the app log".
 * If you see nothing in the logs (and have the debug level setted), may be the app is dying in the process of validating the slack token. slack-bot validates that the request matches with the configured token or the app dies at the very beginning.
* "There is no error in the web server error log, I see some output in the app log (with the debug log level), but i get nothing in my channel/group".
 * Check in the app log for the strings "[DEBUG] Util: group found!" or "[DEBUG] Util: channel found!" . If you can't see those strings, check if your slack authentication token for your team is from an user that have access to the private group you are writing from. 
* I just developed a new command but I am getting a class not found error on CommandFactory.
 * Every time you add a new command (hence a new class), you must update the composer autoloader. just type:
 * php composer.phar update  
* If you have any bug or error to report, feel free to contact me:  luis at digitalicagroup.com

## About Digitalica

We are a small firm focusing on mobile apps development (iOS, Android) and we are passionate about new technologies and ways that helps us work better.
* This project homepage: [slack-bot](https://github.com/digitalicagroup/slack-bot)
* Digitalica homepage: [digitalicagroup.com](http://digitalicagroup.com)
