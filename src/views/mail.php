<?php
/* @var $this AdvancedMailLogger */
?>

<h2>Logs by severity:</h2>

<table>
	<tr>
		<th width="200"></th>
		<th width="100"><?php echo date("m/d", $this->today) ?></th>
		<th width="100"><?php echo date("m/d", $this->yesterday) ?></th>
	</tr>
	<tr>
		<td style="background-color: #ff6d6d">FATAL</td>
		<td style="text-align: right"><?php echo isset($this->byLevelYesterdayArray['FATAL'])?$this->byLevelYesterdayArray['FATAL']:0 ?></td>
		<td style="text-align: right"><?php echo isset($this->byLevelTheDayBeforeArray['FATAL'])?$this->byLevelTheDayBeforeArray['FATAL']:0 ?></td>
	</tr>
	<tr>
		<td style="background-color: #ffaaaa;">ERROR</td>
		<td style="text-align: right"><?php echo isset($this->byLevelYesterdayArray['ERROR'])?$this->byLevelYesterdayArray['ERROR']:0 ?></td>
		<td style="text-align: right"><?php echo isset($this->byLevelTheDayBeforeArray['ERROR'])?$this->byLevelTheDayBeforeArray['ERROR']:0 ?></td>
	</tr>
	<tr>
		<td style="background-color: #ffbc86;">WARN</td>
		<td style="text-align: right"><?php echo isset($this->byLevelYesterdayArray['WARN'])?$this->byLevelYesterdayArray['WARN']:0 ?></td>
		<td style="text-align: right"><?php echo isset($this->byLevelTheDayBeforeArray['WARN'])?$this->byLevelTheDayBeforeArray['WARN']:0 ?></td>
	</tr>
	<tr>
		<td style="background-color: #7eff87;">INFO</td>
		<td style="text-align: right"><?php echo isset($this->byLevelYesterdayArray['INFO'])?$this->byLevelYesterdayArray['INFO']:0 ?></td>
		<td style="text-align: right"><?php echo isset($this->byLevelTheDayBeforeArray['INFO'])?$this->byLevelTheDayBeforeArray['INFO']:0 ?></td>
	</tr>
	<tr>
		<td style="background-color: #caffce;">DEBUG</td>
		<td style="text-align: right"><?php echo isset($this->byLevelYesterdayArray['DEBUG'])?$this->byLevelYesterdayArray['DEBUG']:0 ?></td>
		<td style="text-align: right"><?php echo isset($this->byLevelTheDayBeforeArray['DEBUG'])?$this->byLevelTheDayBeforeArray['DEBUG']:0 ?></td>
	</tr>
	<tr>
		<td>TRACE</td>
		<td style="text-align: right"><?php echo isset($this->byLevelYesterdayArray['TRACE'])?$this->byLevelYesterdayArray['TRACE']:0 ?></td>
		<td style="text-align: right"><?php echo isset($this->byLevelTheDayBeforeArray['TRACE'])?$this->byLevelTheDayBeforeArray['TRACE']:0 ?></td>
	</tr>
</table>

<h2>Logs by category for <?php echo date("m/d", $this->today) ?>:</h2>

<table width="100%">
	<tr>
		<th width="100"></th>
		<th>Category 1</th>
		<?php if ($this->aggregateByCategory>=2): ?><th>Category 2</th><?php endif;?>
		<?php if ($this->aggregateByCategory>=3): ?><th>Category 3</th><?php endif;?>
		<th width="80">Nb logs</th>
	</tr>
	<?php foreach ($this->byCategoryYesterdayArray as $row): 
			switch ($row['log_level']) {
				case "FATAL":
					$bgColor = "#ff6d6d";
					break;
				case "ERROR":
					$bgColor = "#ffaaaa";
					break;
				case "WARN":
					$bgColor = "#ffbc86";
					break;
				case "INFO":
					$bgColor = "#ff6d6d";
					break;
				case "DEBUG":
					$bgColor = "#7eff87";
					break;
				case "TRACE":
					$bgColor = "#ffffff";
					break;
			}
	?>
	<tr style="background-color: <?php echo $bgColor ?>;">
		<td><?php echo $row['log_level'] ?></td>
		<td><?php echo $row['category1'] ?></td>
		<?php if ($this->aggregateByCategory>=2): ?><td><?php echo $row['category2'] ?></td><?php endif;?>
		<?php if ($this->aggregateByCategory>=3): ?><td><?php echo $row['category3'] ?></td><?php endif;?>
		<td style="text-align: right"><?php echo $row['nb_logs'] ?></td>
	</tr>
	<?php endforeach;?>
</table>