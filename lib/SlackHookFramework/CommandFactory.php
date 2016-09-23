<?php

namespace SlackHookFramework;

use Katzgrau\KLogger\Logger;
use Psr\Log\LoggerInterface;

/**
 * Class to handle input parameters parsing and command creation.
 * Aditional command classes must extend AbstractCommand and be
 * added to commands_definition.json
 *
 * @author Luis Augusto Peña Pereira <luis at digitalicagroup dot com>
 *        
 */
class CommandFactory {
	
	/**
	 *
	 * @var array|null
	 */
	protected static $commands = null;
	
	/**
	 *
	 * @var array|null
	 */
	protected static $help_data = null;
	
	/**
	 *
	 * @var LoggerInterface
	 */
	protected static $log = null;
	
	/**
	 * Factory method to create command instances.
	 * New commands should be added to commands_definition.json
	 *
	 * @param array $post
	 *        	Reference to $_POST
	 * @param \SlackHookFramework\Configuration $config
	 *        	Configuration instance with parameters.
	 * @return \SlackHookFramework\AbstractCommand Returns an instance of an AbstractCommand subclass.
	 */
	public static function create($post, $config) {
		$cmd = new CmdUnknown ( $post, $config );
		self::$log = new Logger ( $config->log_dir, $config->log_level );
		self::$log->debug ( "CommandFactory: post received (json encoded): " . json_encode ( $post ) );
		
		// checking if commands definitions have been loaded
		if (self::$commands == null || self::$help_data == null) {
			self::reloadDefinitions ( $config );
		}
		// TODO move strings parameter 'text' to global definition
		if (isset ( $post ['text'] ) && self::$commands != null) {
			self::$log->debug ( "CommandFactory: text received: " . $post ['text'] );
			// the first word represents the command
			if (in_array ( $input [0], array_keys ( self::$commands ) )) {
				$class = self::$commands [$input [0]] ["class"];
				$split_regexp = "/[\\s]+/";
				if (isset ( self::$commands [$input [0]] ["split_regexp"] )) {
					$split_regexp = self::$commands [$input [0]] ["split_regexp"];
				}
				
				// parsing inputs by space
				$input = preg_split ( $split_regexp, $post ['text'] );
				array_shift ( $input );
				
				$cmd = new $class ( $post, $config, $input );
			}
		}
		return $cmd;
	}
	
	/**
	 * Read command definitions from commands_definition.json and custom commands.
	 *
	 * @return boolean returns false if json could not be loaded, true otherwise.
	 */
	public static function reloadDefinitions($config) {
		// Load default commands definitions
		$result = self::reloadFileDefinitions ( __DIR__ . "/commands_definition.json" );
		if ($result) {
			self::$log->debug ( "CommandFactory: default commands_definition.json loaded" );
		} else {
			self::$log->error ( "CommandFactory: Error loading default commands_definition.json, check json format or file permissions." );
		}
		
		// Load custom commands definitions
		$filename = $config->custom_cmds;
		$result = self::reloadFileDefinitions ( $filename, FALSE );
		if ($result) {
			self::$log->debug ( "CommandFactory: custom commands $filename loaded" );
		} else {
			self::$log->warning ( "CommandFactory: Warning . Could not load custom commands from $filename, check json format or file permissions." );
		}
	}
	protected static function reloadFileDefinitions($file, $clean_previous = TRUE) {
		$result = false;
		$json = json_decode ( preg_replace ( '/.+?({.+}).+/', '$1', utf8_encode ( file_get_contents ( $file ) ) ), true );
		if ($json != null) {
			if ($clean_previous) {
				self::$commands = array ();
				self::$help_data = array ();
			}
			foreach ( $json ["commands"] as $command ) {
				self::$commands [$command ["trigger"]] ["class"] = $command ["class"];
				self::$commands [$command ["trigger"]] ["split_regexp"] = $command ["split_regexp"];
				self::$help_data [$command ["help_title"]] = $command ["help_text"];
			}
			$result = true;
		}
		return $result;
	}
	public static function getHelpData() {
		if (self::$help_data == null) {
			self::reloadDefinitions ();
		}
		return self::$help_data;
	}
}
