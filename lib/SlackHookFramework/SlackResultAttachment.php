<?php

namespace SlackHookFramework;

/**
 * Class to be used as an attachment in a message to slack.
 *
 * @author Luis Augusto Peña Pereira <lpenap at gmail dot com>
 *        
 */
class SlackResultAttachment extends AbstractArray {
	public function __construct() {
		parent::__construct ();
		$this->a [SlackResult::R_MRKDWN_IN] = array (
				SlackResult::R_PRETEXT,
				SlackResult::R_TEXT,
				SlackResult::R_TITLE,
				SlackResult::R_FALLBACK,
				SlackResult::R_FIELDS 
		);
	}
	public function setTitle($title) {
		$this->a [SlackResult::R_TITLE] = $title;
	}
	public function setFallback($fallback) {
		$this->a [SlackResult::R_FALLBACK] = $fallback;
	}
	public function setPretext($pretext) {
		$this->a [SlackResult::R_PRETEXT] = $pretext;
	}
	public function setText($text) {
		$this->a [SlackResult::R_TEXT] = $text;
	}
	public function getText() {
		return $this->a [SlackResult::R_TEXT];
	}
	public function setMrkdwnArray($arr) {
		$this->a [SlackResult::R_MRKDWN_IN] = $arr;
	}
	public function setFieldsArray($fields) {
		$this->setArray ( SlackResult::R_FIELDS, $fields );
	}
}

?>