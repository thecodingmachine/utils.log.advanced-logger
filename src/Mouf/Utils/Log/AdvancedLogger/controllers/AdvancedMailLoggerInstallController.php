<?php
namespace Mouf\Utils\Log\AdvancedLogger\controllers;


use Mouf\Html\Template\TemplateInterface;
use Mouf\Mvc\Splash\Controllers\Controller;
use Mouf\MoufManager;
/**
 * The controller managing the install process.
 * It will query the database details.
 *
 * @Component
 */
class AdvancedMailLoggerInstallController extends Controller  {
	public $selfedit;
	
	/**
	 * The active MoufManager to be edited/viewed
	 *
	 * @var MoufManager
	 */
	public $moufManager;
	
	/**
	 * The template used by the main page for mouf.
	 *
	 * @Property
	 * @Compulsory
	 * @var TemplateInterface
	 */
	public $template;
	
	/**
	 * Displays the first install screen.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function defaultAction($selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
				
		$this->template->addContentFile(dirname(__FILE__)."/../views/installStep1.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Skips the install process.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only)
	 */
	public function skip($selfedit = "false") {
		InstallUtils::continueInstall($selfedit == "true");
	}

	protected $dbStatsInstance;
	protected $mailServiceInstance;
	protected $from;
	protected $to;
	protected $aggregateByCategory;
	protected $title;
	
	/**
	 * Displays the second install screen.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function configure($selfedit = "false") {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$this->dbStatsInstance = "logstats_log_stats";
		$this->mailServiceInstance = "smtpMailService";
		$this->title = "Log stats";
		$this->loggerInstanceName = "errorLogLogger";
		$this->aggregateByCategory = 1;
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/installStep2.php", $this);
		$this->template->draw();
	}
	
	
	
	/**
	 * Action to create the database connection.
	 * 
	 * @Action
	 * @Logged
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only)
	 */
	public function install($dbStatsInstance, $mailServiceInstance, $aggregateByCategory, $from, $to, $selfedit = "false") {
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$moufManager = $this->moufManager;
		$configManager = $moufManager->getConfigManager();
		
		$constants = $configManager->getMergedConstants();
		
		if (!isset($constants['ADVANCED_STATS_LOGGER_FROM'])) {
			$configManager->registerConstant("ADVANCED_STATS_LOGGER_FROM", "string", "", "The 'from' mail address that will be used to send mails containing the stats about the logs.");
		}
		
		if (!isset($constants['ADVANCED_STATS_LOGGER_TO'])) {
			$configManager->registerConstant("ADVANCED_STATS_LOGGER_TO", "string", "", "The 'to' mail addresss that will receive the mails containing the stats about the logs. If more than one mail is used, the list must be comma-separated.");
		}
		
		if (!$moufManager->instanceExists("advancedMailLogger")) {
			$moufManager->declareComponent("advancedMailLogger", "AdvancedMailLogger");
			$moufManager->bindComponent("advancedMailLogger", "dbStats", $dbStatsInstance);
			$moufManager->bindComponent("advancedMailLogger", "mailService", $mailServiceInstance);
			$moufManager->setParameter("advancedMailLogger", "from", "ADVANCED_STATS_LOGGER_FROM", "config");
			$moufManager->setParameter("advancedMailLogger", "to", "ADVANCED_STATS_LOGGER_TO", "config");
			$moufManager->setParameter("advancedMailLogger", "aggregateByCategory", 2);
			
		}
		
		$configPhpConstants = $configManager->getDefinedConstants();
		$configPhpConstants['ADVANCED_STATS_LOGGER_FROM'] = $from;
		$configPhpConstants['ADVANCED_STATS_LOGGER_TO'] = $to;
		$configManager->setDefinedConstants($configPhpConstants);
		
		$moufManager->rewriteMouf();		
		
		InstallUtils::continueInstall($selfedit == "true");
	}
	
}