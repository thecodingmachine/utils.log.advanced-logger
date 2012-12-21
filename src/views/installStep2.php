<?php /* @var $this SmtpMailServiceInstallController */ ?>

<h1>Configure the advanced mail logger</h1>

<form action="install">
<input type="hidden" id="selfedit" name="selfedit" value="<?php echo plainstring_to_htmlprotected($this->selfedit) ?>" />

<p>The object used to create log stats:</p>
<?php 
MoufHtmlHelper::drawInstancesDropDown("Log DB Stats", "dbStatsInstance", "DB_Stats", false, $this->loggerInstanceName);
?>

<p>The service used to send mails:</p>
<?php 
MoufHtmlHelper::drawInstancesDropDown("Mail service", "mailServiceInstance", "MailServiceInterface", false, $this->mailServiceInstance);
?>

<p>The aggregation level for stats in the logs.</p>
<div>
	<label for="aggregateByCategory">Aggregate by:</label>
	<select id="aggregateByCategory" name="aggregateByCategory">
		<option value="1">Category 1</option>
		<option value="2">Category 1 + Category 2</option>
		<option value="3">Category 1 + Category 2 + Category 3</option>
	</select>
</div>

<p>The mail will seem to be sent by:</p>
<div>
	<label for="from">From:</label>
	<input type="text" id="from" name="from" value="<?php echo plainstring_to_htmlprotected($this->from) ?>" />
</div>

<p>The mail will seem to be sent to (comma separated list of mail addresses):</p>
<div>
	<label for="to">To:</label>
	<input type="text" id="to" name="to" value="<?php echo plainstring_to_htmlprotected($this->to) ?>" />
</div>


<div>
	<button name="action" value="install" type="submit">Next</button>
</div>
</form>