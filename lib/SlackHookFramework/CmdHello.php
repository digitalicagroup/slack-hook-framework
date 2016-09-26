<?php

namespace SlackHookFramework;

/**
 * Class to show usage of the slack-hook-framework.
 *
 * @author Luis Augusto Peña Pereira <luis at digitalicagroup dot com>
 *        
 */
class CmdHello extends AbstractCommand {
	
	/**
	 * Factory method to be implemented from \SlackHookFramework\AbstractCommand .
	 * Must return an instance of \SlackHookFramework\SlackResult .
	 *
	 * Basically, the method returns an instance of SlackResult.
	 * Inside a single instance of SlackResult, several
	 * SlackResultAttachment instances can be stored.
	 * Inside a SlackResultAttachment instance, several
	 * SlackResultAttachmentField instances can be stored.
	 * The result is then formating according to the Slack
	 * formating guide.
	 *
	 * So you must process your command here, and then
	 * prepare your SlackResult instance.
	 *
	 * @see \SlackHookFramework\AbstractCommand::executeImpl()
	 * @return \SlackHookFramework\SlackResult
	 */
	protected function executeImpl($params) {
		/**
		 * Get a reference to the log.
		 */
		$log = $this->log;
		
		/**
		 * Output some debug info to log file.
		 */
		$log->debug ( "CmdHello: Parameters received: " . implode ( ",", $params ) );
		
		/**
		 * Preparing the result text and validating parameters.
		 */
		$resultText = "[requested by " . $this->post ["user_name"] . "]";
		if (empty ( $params )) {
			$resultText .= " You must specify at least one parameter!";
		} else {
			$resultText .= " CmdHello Result: ";
		}
		
		/**
		 * Preparing attachments.
		 */
		$attachments = array ();
		
		/**
		 * Cycling through parameters, just for fun.
		 */
		foreach ( $params as $param ) {
			$log->debug ( "CmdHello: processing parameter $param" );
			
			/**
			 * Preparing one result attachment for processing this parameter.
			 */
			$attachment = new SlackResultAttachment ();
			$attachment->setTitle ( "Processing $param" );
			$attachment->setText ( "Hello $param !!" );
			$attachment->setFallback ( "fallback text." );
			$attachment->setPretext ( "pretext here." );
			
			/**
			 * Adding some fields to the attachment.
			 */
			$fields = array ();
			$fields [] = SlackResultAttachmentField::withAttributes ( "Field 1", "Value" );
			$fields [] = SlackResultAttachmentField::withAttributes ( "Field 2", "Value" );
			$fields [] = SlackResultAttachmentField::withAttributes ( "This is a long field", "this is a long Value", FALSE );
			$attachment->setFieldsArray ( $fields );
			
			/**
			 * Adding the attachment to the attachments array.
			 */
			$attachments [] = $attachment;
		}
		
		$this->setResultText ( $resultText );
		$this->setSlackResultAttachments ( $attachments );
		return $result;
	}
}
