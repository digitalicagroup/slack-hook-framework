<?php


namespace SlackHookFramework;

use Katzgrau\KLogger\Logger;
use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class Installer {
	public static function postUpdate (Event $event) {
		echo "postUpdate-";
		print_r($event->getComposer()->getConfig()->all());
		echo "postUpdate-";
	}
	
	public static function postPackageUpdate (PackageEvent $event) {
		echo "postPackageUpdate-";
		print_r($event->getComposer()->getConfig()->all());
		echo "postPackageUpdate-";
	}
}