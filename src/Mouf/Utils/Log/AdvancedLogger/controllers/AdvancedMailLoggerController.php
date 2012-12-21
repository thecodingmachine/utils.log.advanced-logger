<?php
namespace Mouf\Utils\Log\AdvancedLogger\controllers;

use Mouf\Html\Template\TemplateInterface;
use Mouf\Reflection\MoufReflectionProxy;
use Mouf\Mvc\Splash\Controllers\Controller;
/**
 * The controller used by the avanced mail logger.
 *
 * @Component
 */
class AdvancedMailLoggerController extends Controller {

	protected $selfedit;
	
	/**
	 * The default template to use for this controller (will be the mouf template)
	 *
	 * @Property
	 * @Compulsory 
	 * @var TemplateInterface
	 */
	public $template;
	
	/**
	 * Admin page used to enable or disable label edition.
	 *
	 * @Action
	 * @Logged
	 */
	public function defaultAction() {
		$this->template->addContentFile(dirname(__FILE__)."/../views/cron.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Show the stats.
	 *
	 * @Action
	 * @Logged
	 * @param string $name
	 * @param string $selfedit
	 */
	public function showStats($name, $selfedit = "false") {
		$advancedMailLogger = MoufProxy::getInstance($name);
		/* @var $advancedMailLogger AdvancedMailLogger */
		$this->template->addContentText($advancedMailLogger->getHtmlForMail());
		
		$this->template->draw();
	}
	
	
	/**
	 * Admin page used to send mails manually.
	 *
	 * @Action
	 * @Logged
	 */
	public function triggerMails() {
		$this->template->addContentFile(dirname(__FILE__)."/../views/triggerMails.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Action to send mails.
	 *
	 * @Action
	 * @Logged
	 */
	public function doTriggerMails() {
		$response = self::performRequest(MoufReflectionProxy::getLocalUrlToProject()."plugins/utils/log/advanced_logger/1.0/cron.php");
		if ($response) {
			throw new \Exception($response);
		}
		header("Location: ".ROOT_URL."mouf/");		
	}
	
	private static function performRequest($url) {
		// preparation de l'envoi
		$ch = curl_init();
				
		curl_setopt( $ch, CURLOPT_URL, $url);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_POST, FALSE );
		$response = curl_exec( $ch );
		
		if( curl_error($ch) ) { 
			throw new \Exception("An error occured: ".curl_error($ch));
		}
		curl_close( $ch );
		
		return $response;
	}
}

?>
