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
	 * Factory method to be implemented from \SlackHookFramework\AbstractCommand.
	 *
	 * This method should execute your command's logic.
	 *
	 * There are several ways to return information to Slack:
	 *
	 * 1) Simply use $this->setResultText("string here"); to return a single
	 * line to slack.
	 *
	 * 2) Create an array with one or more instances of SlackResultAttachment
	 * with relevant information and formatting options. Add this array using
	 * $this->setSlackResultAttachments(myArray); .
	 *
	 * 3) Add an array with one or more instances of SlackResultAttachmentField
	 * to each one of your attachments to include even more detailed information.
	 *
	 * 4) Complete override the internal reference $this->result with your
	 * own SlackResult instance if you want more control over your result.
	 *
	 * @param String[] $params
	 *        	An array of strings with the parameters already
	 *        	parsed for your command (without the command trigger). If you didn't
	 *        	defined a split_regexp field in your custom_cmds.json, the paramters
	 *        	are parsed by one or consecutive space characters after detecting
	 *        	your command trigger.
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
			$attachment->setColor ( "#00ff00" );
			
			/**
			 * Adding some fields to the attachment.
			 */
			$fields = array ();
			$fields [] = SlackResultAttachmentField::withAttributes ( "Short Field", "Short Field Value" );
			$fields [] = SlackResultAttachmentField::withAttributes ( "This is a long field", "this is a long Value", FALSE );
			$attachment->setFieldsArray ( $fields );
			
			/**
			 * Adding the attachment to the attachments array.
			 */
			$attachments [] = $attachment;
		}
		
		$this->setResultText ( $resultText );
		$this->setSlackResultAttachments ( $attachments );
	
	/**
	 * If you want more control, you can create your own instance of
	 * SlackResult and override $this->result with your own object.
	 */
	}
}
