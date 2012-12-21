<h1>Advanced Mail Logger cron setup</h1>

<h2>Using CRON</h2>
<p>You want to register the Advanced Mail Logger as a cron task that will run periodically. Otherwise, the mail containing the log stats would not be sent automatically.</p>
<p>If you are running a Linux machine, the easiest way to do this is to use cron.</p>
<p>To register a new cron task, use the <code>crontab -e</code> command.</p>
<p>Note: you might want to run this command as the Apache user if you want to have the same access rights than Apache (in this case, you can use <code>su www-data -c "crontab -e"</code>)</p>

<p>Inside the editor, add this line:</p>

<pre>
10 0 * * * /usr/bin/php <?php echo ROOT_PATH ?>plugins/utils/log/advanced_logger/1.0/cron.php
</pre>

<p>This will send the mails every night, at midnight past ten.</p>