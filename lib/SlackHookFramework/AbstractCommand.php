<?php

namespace SlackHookFramework;

use SlackHookFramework\SlackResult;
use SlackHookFramework\SlackResultAttachment;
use SlackHookFramework\SlackResultAttachmentField;
use Katzgrau\KLogger\Logger;

/**
 * Abstract class to extend into other commands.
 * It stores the arguments, configuration, post parameters
 * and result.
 *
 * @author Luis Augusto Peña Pereira <luis at digitalicagroup dot com>
 *        
 */
abstract class AbstractCommand {
	/**
	 * Reference to $_POST parameters.
	 */
	protected $post;
	
	/**
	 * Configuration parameters.
	 *
	 * @var Configuration
	 */
	protected $config;
	
	/**
	 * Logger facility.
	 *
	 * @var Logger
	 */
	protected $log;
	
	/**
	 * Array of input parameters to the command.
	 * This array does not contains the word referencing
	 * the command itself, only the strings after that
	 * first word.
	 *
	 * @var array of strings
	 */
	protected $cmd;
	
	/**
	 * Boolean to post (or not) the response to the originator's
	 * channel or group.
	 *
	 * @var bool
	 */
	protected $response_to_source_channel;
	
	/**
	 * Result of executing the command.
	 *
	 * @var SlackResult
	 */
	private $result;
	
	/**
	 * Construtor.
	 *
	 * @param array $post
	 *        	Reference to $_POST parameters.
	 * @param Configuration $config
	 *        	Configuration parameters.
	 * @param array $arr
	 *        	String array containing this command input parameters.
	 */
	public function __construct($post, $config, $arr = array()) {
		$this->log = new Logger ( $config->log_dir, $config->log_level );
		$this->post = $post;
		$this->config = $config;
		$this->cmd = $arr;
		$this->response_to_source_channel = true;
		$this->result = new SlackResult ();
		$attTest = new SlackResultAttachment();
		$attTest = new SlackResultAttachmentField();
	}
	
	/**
	 * Function to be implemented by command subclasses.
	 * Should return a proper instance of SlackResult class.
	 */
	abstract protected function executeImpl();
	
	/**
	 * Executes this command, and returns a new SlackResult instance.
	 * TODO move channel_id string to global config.
	 *
	 * @return \SlackHookFramework\SlackResult
	 */
	public function execute() {
		$this->log->debug ( "AbstractCommand (" . get_class ( $this ) . "): command array: {" . implode ( ",", $this->cmd ) . "}" );
		$this->result = $this->executeImpl ();
		
		if ($this->response_to_source_channel) {
			$this->log->debug ( "AbstractCommand (" . get_class ( $this ) . "): requesting channel name for channel: " . $this->post ["channel_id"] );
			$this->result->setChannel ( Util::getChannelName ( $this->config, $this->post ["channel_id"] ) );
		}
		return $this->result;
	}
	
	/**
	 * Function to set whether or not to post results to original channel or group.
	 * If false, no response will be posted to the Incoming WebHook.
	 *
	 * @param bool $bool        	
	 */
	public function setResponseToSourceChannel($bool) {
		$this->response_to_source_channel = $bool;
	}
	
	/**
	 * Post the SlackResult json representation to the Slack Incoming WebHook.
	 */
	public function post() {
		$json = $this->result->toJson ();
		$this->log->debug ( "AbstractCommand (" . get_class ( $this ) . "): response json: $json" );
		$result = Util::post ( $this->config->slack_webhook_url, $json );
		if (! $result) {
			$this->log->error ( "AbstractCommand: Error sending json: $json to slack hook: " . $this->config->slack_webhook_url );
		}
		return $result;
	}
}
