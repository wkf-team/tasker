<?php
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'cssFile' => 'css/grid.css',
	'attributes'=>array(
		[
			'label'=>CHtml::activeLabel($model, "start_date"),
			'value'=>$model->EncodeDate($model->start_date),
		],
		[
			'label'=>CHtml::activeLabel($model, "story_points"),
			'value'=>$model->getTotalSP(),
		],
		'status.name:text:'.CHtml::activeLabel($model, "status_id"),
	),
));
$this->widget('TW_TicketHList', [
	'dataProvider'=>new CActiveDataProvider('Ticket', [
		'data'=>$model->tickets,
		'pagination'=>false
	]),
]);
?>
