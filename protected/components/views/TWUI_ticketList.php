<div class="task-inner-lists" style="color:black;">
	<div class="summary_custome">Displaying 1-<?
	$count = $dataProvider->getTotalItemCount();
	echo "$count of $count";
	?> results.</div>
	<?php $this->widget('TW_TicketHList', array(
		'dataProvider'=>$dataProvider,
		'noChildren'=>$noChildren
	)); ?>
</div>