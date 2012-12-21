<?php
require_once '../../../../mouf/Mouf.php';

// TODO: add support for selfedit.

$moufManager = MoufManager::getMoufManager();

$instanceNames = $moufManager->findInstances("Mouf\\Utils\\Log\\AdvancedLogger\\AdvancedMailLogger");

foreach ($instanceNames as $instanceName) {
	/* @var $advancedMailLogger AdvancedMailLogger */
	$advancedMailLogger = $moufManager->getInstance($instanceName);
	$advancedMailLogger->sendMail();
}