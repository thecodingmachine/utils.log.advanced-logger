<?php
// Install controller declaration
MoufManager::getMoufManager()->declareComponent('advancedmailloggerinstall', 'Mouf\\Utils\\Log\\AdvancedLogger\\controllers\\AdvancedMailLoggerInstallController', true);
MoufManager::getMoufManager()->bindComponents('advancedmailloggerinstall', 'template', 'installTemplate');

MoufUtils::registerMainMenu('utilsMainMenu', 'Utils', null, 'mainMenu', 200);
MoufUtils::registerMenuItem('utilsAdvencedMailLoggerSubMenu', 'Advanced Mail Logger', null, 'utilsMainMenu', 60);
MoufUtils::registerMenuItem('utilsAdvancedLoggerViewStats2MenuItem', 'View log stats', 'javascript:chooseInstancePopup("AdvancedMailLogger", "'.ROOT_URL.'mouf/advancedlogger/showStats?name=", "'.ROOT_URL.'")', 'utilsAdvencedMailLoggerSubMenu', 10);
MoufUtils::registerMenuItem('utilsAdvancedLoggerCron2MenuItem', 'Install advanced logger CRON', 'advancedlogger/', 'utilsAdvencedMailLoggerSubMenu', 20);
MoufUtils::registerMenuItem('utilsAdvancedLoggerCron2MenuItem', 'Manually send log stats', 'advancedlogger/triggerMails', 'utilsAdvencedMailLoggerSubMenu', 30);

// Controller declaration
MoufManager::getMoufManager()->declareComponent('advancedlogger', 'Mouf\\Utils\\Log\\AdvancedLogger\\controllers\\AdvancedMailLoggerController', true);
MoufManager::getMoufManager()->bindComponents('advancedlogger', 'template', 'moufTemplate');
?>