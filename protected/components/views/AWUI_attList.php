<?php
echo CHtml::link("Add new attachment", array('attachment/create', 'ticket_id'=>$ticket_id), array('id'=>'btnAddAttachment'));
if ($dataProvider->totalItemCount > 0) {
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'attachment-grid',
		'cssFile' => 'css/gridView.css',
		'dataProvider'=>$dataProvider,
		'columns'=>array(
			array(
				'name' => 'name',
				'value' => 'CHtml::link($data->name, "attachments/".$data->ticket_id."/".$data->name)',
				'type' => 'html'
			),
			array(
				'name' => 'author_id',
				'value' => '$data->author->name',
				'type' => 'text'
			),
			'create_date',
			array(
				'class'=>'CButtonColumn',
				'buttons'=>array(
					'view' => array('visible'=>'false'),
					'update' => array('visible'=>'false'),
					'delete' => array('visible'=>'User::CheckLevel(20)'),//allow coordinator
				),
				'deleteButtonUrl'=>"CHtml::normalizeUrl(array('attachment/delete', 'id'=>\$data->id))",
			),
		),
	));
}
?>
<script>
$(function () {
	$("#btnAddAttachment").button({
      icons: {
        primary: "ui-icon-plusthick"
      },
      text: false
    });
});
</script>
