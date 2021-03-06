<?php

namespace SlackHookFramework;

/**
 * Class to parse help data (loaded by CommandFactory) into a SlackResult instance
 * containing all available commands into fields of a single attachment.
 *
 * @author Luis Augusto Peña Pereira <luis at digitalicagroup dot com>
 *        
 */
class CmdHelp extends AbstractCommand {
	protected function executeImpl($params) {
		$log = $this->log;
		$this->setResultText ( "Help" );
		$att = new SlackResultAttachment ();
		$att->setTitle ( "Available Commands:" );
		$att->setFallback ( "Available Commands:" );
		
		$fields = array ();
		$help_data = CommandFactory::getHelpData ();
		if ($help_data == null) {
			$log->error ( "CmdHelp: Error loading help data, check commands_definition.json format or file permissions" );
		} else {
			$help_keys = array_keys ( $help_data );
			foreach ( $help_keys as $key ) {
				$fields [] = SlackResultAttachmentField::withAttributes ( $key, $help_data [$key], false );
			}
		}
		usort ( $fields, array (
				"SlackHookFramework\\SlackResultAttachmentField",
				"compare" 
		) );
		$att->setFieldsArray ( $fields );
		$this->setSlackResultAttachments ( array (
				$att 
		) );
	}
}
