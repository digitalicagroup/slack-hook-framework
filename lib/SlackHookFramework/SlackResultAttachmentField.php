<?php

namespace SlackHookFramework;

/**
 * Class to be used as field in an attachment.
 *
 * @author Luis Augusto Peña Pereira <luis at digitalicagroup dot com>
 *        
 */
class SlackResultAttachmentField extends AbstractArray {
	public static function withAttributes($title, $value, $isShort = true) {
		$instance = new self ();
		$instance->setTitle ( $title );
		$instance->setValue ( $value );
		$instance->setShort ( $isShort );
		return $instance;
	}
	public function setTitle($title) {
		$this->a [SlackResult::R_TITLE] = $title;
	}
	public function setValue($value) {
		$this->a [SlackResult::R_VALUE] = $value;
	}
	public function setShort($isShort) {
		$this->a [SlackResult::R_SHORT] = $isShort;
	}
	public static function compare($a, $b) {
		$al = strtolower ( $a->getValue ( SlackResult::R_TITLE ) );
		$bl = strtolower ( $b->getValue ( SlackResult::R_TITLE ) );
		if ($al == $bl) {
			return 0;
		}
		return ($al > $bl) ? + 1 : - 1;
	}
}

?>