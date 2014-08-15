<div style="position:relative;height:40px;">
<span style='color:black;'>Ссылки сюда</span>
<?php echo CHtml::link("Add new to link", array('relation/create', 'ticket_to_id'=>$ticket_id), array('id'=>'btnAddToLink', 'style'=>'position:absolute; right:10px;')); ?>
</div>
<?php
if ($toProvider->totalItemCount > 0) {
	$this->widget('zii.widgets.grid.CGridView', array(
		'cssFile' => 'css/gridView.css',
		'dataProvider'=>$toProvider,
		'columns'=>array(
			array(
				'name' => 'Данная задача:',
				'value' => '$data->relationType->reverse_name',
				'type' => 'text'
			),
			array(
				'name' => 'ticket_from_id',
				'value' => 'CHtml::link("#".$data->ticket_from_id.". ".$data->ticketFrom->subject, array("ticket/view", "id"=>$data->ticket_from_id))',
				'type' => 'html'
			),
			array(
				'name' => CHtml::activeLabel(Ticket::model(), 'status_id'),
				'value' => '$data->ticketFrom->status->name',
				'type' => 'text'
			),
			array(
				'name' => CHtml::activeLabel(Ticket::model(), 'due_date'),
				'value' => '$data->ticketFrom->encodeDate($data->ticketFrom->due_date)',
				'type' => 'text'
			),
			array(
				'class'=>'CButtonColumn',
				'buttons'=>array(
					'view' => array('visible'=>'false'),
					'update' => array('visible'=>'false'),
					'delete' => array('visible'=>'User::CheckLevel(10)'),//allow participant
				),
				'deleteButtonUrl'=>"CHtml::normalizeUrl(array('relation/delete', 'id'=>\$data->id))",
			),
		),
	));
}
?>
<div style="position:relative;height:40px;">
<span style='color:black;'>Ссылки отсюда</span>
<?php echo CHtml::link("Add new from link", array('relation/create', 'ticket_from_id'=>$ticket_id), array('id'=>'btnAddFromLink', 'style'=>'position:absolute; right:10px; bottom:3px;')); ?>
</div>
<?php
if ($fromProvider->totalItemCount > 0) {
	$this->widget('zii.widgets.grid.CGridView', array(
		'cssFile' => 'css/gridView.css',
		'dataProvider'=>$fromProvider,
		'columns'=>array(
			array(
				'name' => 'Данная задача:',
				'value' => '$data->relationType->direct_name',
				'type' => 'text'
			),
			array(
				'name' => 'ticket_to_id',
				'value' => 'CHtml::link("#".$data->ticket_to_id.". ".$data->ticketTo->subject, array("ticket/view", "id"=>$data->ticket_to_id))',
				'type' => 'html'
			),
			array(
				'name' => CHtml::activeLabel(Ticket::model(), 'status_id'),
				'value' => '$data->ticketTo->status->name',
				'type' => 'text'
			),
			array(
				'name' => CHtml::activeLabel(Ticket::model(), 'due_date'),
				'value' => '$data->ticketTo->encodeDate($data->ticketTo->due_date)',
				'type' => 'text'
			),
		),
	));
}
?>

<script>
$(function () {
	$("#btnAddFromLink").button({
      icons: {
        primary: "ui-icon-plusthick"
      },
      text: false
    });
	$("#btnAddToLink").button({
      icons: {
        primary: "ui-icon-plusthick"
      },
      text: false
    });
});
</script>