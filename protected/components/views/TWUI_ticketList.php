<table style="background:rgb(51,51,51);">
<tr>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('id'));?></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('ticket_type_id'));?></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('subject'));?></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('owner_user_id'));?></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('due_date'));?></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('priority_id'));?></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('project_id'));?></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('status_id'));?></th>
</tr>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'application.views.ticket._view',
)); ?>
</table>