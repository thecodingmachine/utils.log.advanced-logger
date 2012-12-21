<?php
namespace Mouf\Utils\Log\AdvancedLogger;

use Mouf\Database\Dbstats\DB_stats;
use Mouf\Utils\Mailer\MailServiceInterface\MailServiceInterface;
use Mouf\Utils\Mailer\MailServiceInterface\Mail;
use Mouf\Utils\Mailer\MailServiceInterface\MailAddress;
/**
 * The AdvancedMailLogger is an object in charge of regularly sending mails to
 * the admin containing detailed reports of the logging activity on a server.
 * 
 * @author David Negrier
 * @Component
 * @ExtendedAction {"name":"View stats", "url":"mouf/advancedlogger/showStats", "default":false}
 */
class AdvancedMailLogger {
	
	/**
	 * The object representing the stats table.
	 * 
	 * @Property
	 * @Compulsory
	 * @var DB_Stats
	 */
	public $dbStats;
	
	/**
	 * The name of the table containing the list of tasks.
	 * Defaults to "tasks".
	 * 
	 * @Property
	 * @Compulsory
	 * @var MailServiceInterface
	 */
	public $mailService;

	/**
	 * The "from" email adress sending that mail.
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $from;

	/**
	 * A comma separated list of email adress that will receive the mail.
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $to;
	
	/**
	 * The title for the mail.
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $title = "Log stats";
	
	/**
	 * Aggregates the number of logs by category1, or category1+category2 or category1+category2+category3
	 * Selecting only category1 will display less rows (more aggregated data).
	 * 
	 * @Property
	 * @Compulsory
	 * @OneOf("1","2","3")
	 * @var int
	 */
	public $aggregateByCategory = 1;
	
	protected $byLevelYesterdayArray = array();
	protected $byLevelTheDayBeforeArray = array();
	protected $byCategoryYesterdayArray = array();
	
	protected $today;
	protected $yesterday;
	
	/**
	 * Returns the HTML for the stats sent by mail.
	 *  
	 * @param timestamp $time The stats are for today
	 */
	public function getHtmlForMail($time = null) {
		if ($time==null) {
			$time = time();
		}
		
		$this->today = $time;
		$this->yesterday = strtotime('-1 day', $time);
		
		$sql = "SELECT *
				FROM `logstats`
				WHERE `year` = ".date("Y", $this->today)."
				AND `month` = ".date("n", $this->today)."
				AND `day` = ".date("j", $this->today)."
				AND `hour` IS NULL
				AND `category1` IS NULL
				AND `category2` IS NULL
				AND `category3` IS NULL
				AND `log_level` IS NOT NULL"; 
		
		$byLevelYesterdayResultSet = $this->dbStats->dbConnection->getAll($sql);

		foreach ($byLevelYesterdayResultSet as $row) {
			$this->byLevelYesterdayArray[$row['log_level']] = $row['nb_logs'];
		}
		
		$sql = "SELECT *
				FROM `logstats`
				WHERE `year` = ".date("Y", $this->yesterday)."
				AND `month` = ".date("n", $this->yesterday)."
				AND `day` = ".date("j", $this->yesterday)."
				AND `hour` IS NULL
				AND `category1` IS NULL
				AND `category2` IS NULL
				AND `category3` IS NULL
				AND `log_level` IS NOT NULL"; 
		
		$byLevelTheDayBeforeResultSet = $this->dbStats->dbConnection->getAll($sql);

		foreach ($byLevelTheDayBeforeResultSet as $row) {
			$this->byLevelTheDayBeforeArray[$row['log_level']] = $row['nb_logs'];
		}
		
		$sql = "SELECT `category1`, `category2`, `category3`, `log_level`, `nb_logs` 
			FROM `logstats` 
			WHERE `year` = ".date("Y", $this->today)." 
			AND `month` = ".date("n", $this->today)." 
			AND `day` = ".date("j", $this->today)." 
			AND `hour` IS NULL 
			AND `category1` IS NOT NULL 
			AND `category2` IS ".(($this->aggregateByCategory>=2)?"NOT":"")." NULL
			AND `category3` IS ".(($this->aggregateByCategory>=3)?"NOT":"")." NULL
			AND `log_level` IS NOT NULL 
			ORDER BY `log_level` ASC, `category1` ASC, `category2` ASC, `category3` ASC";
		
		$this->byCategoryYesterdayArray = $this->dbStats->dbConnection->getAll($sql);
		
		ob_start();
		include('views/mail.php');
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	/**
	 * Compute stats and sends the mail containing the logs stats.
	 */
	public function sendMail() {
		
		
		$mail = new Mail();
		$mail->setTitle($this->title);
		$mail->setFrom(new MailAddress($this->from));
		$toArray = explode(",", $this->to);
		foreach ($toArray as $to) {
			$mail->addToRecipient(new MailAddress(trim($to)));
		}
		
		$html = $this->getHtmlForMail(strtotime('-1 day'));
		
		$mail->setBodyHtml($html);
		$this->mailService->send($mail);
		//echo $html;
	}
	
}