<?php

namespace SlackHookFramework;

/**
 * Class to define accessor methods to the parent json element present
 * in a payload to the slack incoming webhook.
 * Each payload consists of one instance of SlackResult.
 * In a SlackResult instance, an array of SlackResultAttachment
 * can be stored to represent "attachments" in a message to slack.
 * In a SlackResultAttachment, an array of SlackResultAttachmentField
 * can be stored to represent "fields" in the given attachment of a message to slack.
 *
 * @author Luis Augusto Peña Pereira <lpenap at gmail dot com>
 *        
 */
class SlackResult extends AbstractArray {
	const R_MRKDWN = 'mrkdwn';
	const R_TEXT = 'text';
	const R_ATT = 'attachments';
	const R_CHANNEL = 'channel';
	const R_TITLE = 'title';
	const R_FALLBACK = 'fallback';
	const R_PRETEXT = 'pretext';
	const R_MRKDWN_IN = 'mrkdwn_in';
	const R_FIELDS = 'fields';
	const R_VALUE = 'value';
	const R_SHORT = 'short';
	
	public function __construct() {
		parent::__construct ();
		$this->a [self::R_MRKDWN] = true;
	}
	public function setText($text) {
		$this->a [self::R_TEXT] = $text;
	}
	public function setMrkdwn($mrkdwn) {
		$this->a [self::R_MRKDWN] = $mrkdwn;
	}
	public function setAttachmentsArray($att) {
		$this->setArray (self::R_ATT, $att );
	}
	public function setChannel($channel) {
		$this->a [self::R_CHANNEL] = $channel;
	}
}

?>