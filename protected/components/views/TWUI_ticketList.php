<div class="task-inner-lists" style="color:black;">
	<div class="summary_custome">Displaying 1-1 of <?= $dataProvider->getTotalItemCount() ?> results.</div>
	<?php $this->widget('TW_TicketHList', array(
		'dataProvider'=>$dataProvider,
		'noChildren'=>$noChildren
	)); ?>
</div>