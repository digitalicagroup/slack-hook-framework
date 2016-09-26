<?php

namespace SlackHookFramework;

/**
 * Default command to be executed when the input parameters
 * can't be assigned to any command.
 *
 * @author Luis Augusto Peña Pereira <luis at digitalicagroup dot com>
 *        
 */
class CmdUnknown extends AbstractCommand {
	protected function executeImpl() {
		$this->setResultText ( 'Unknown Command' );
		$this->log->debug ( "CmdUnknown: Executing CmdUnknown" );
		return $result;
	}
} 
