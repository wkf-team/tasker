<?php
/* @var $this TicketController */
/* @var $data Ticket */
?>
<tr>


	<td>
		<!-- Type icon -->
		<span class="ui-icon <?
			switch ($data->ticket_type_id) {
				case 1: echo "ui-icon-flag"; break;
				case 2: echo "ui-icon-note"; break;
				case 3: echo "ui-icon-bullet"; break;
				case 4: echo "ui-icon-notice"; break;
			}
		?>" style="display:inline-block;"></span>
		<!-- id and subject -->
		<?
		echo CHtml::link(CHtml::encode($data->id.". ".$data->subject), array('ticket/view', 'id'=>$data->id));
		?>
	</td>

	<td><?php echo CHtml::encode($data->ownerUser->name); ?>
	</td>

	<td><?php echo $data->encodeDate($data->due_date); ?>
	</td>

	<td><?php echo CHtml::encode($data->priority->name); ?>
	</td>

	<td><?php echo CHtml::encode($data->project->name); ?>
	</td>

	<td><?php echo CHtml::encode($data->status->name); ?>
	</td>

	<?php /*
	<td><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	</td>

	<td><?php echo CHtml::encode($data->getAttributeLabel('create_date')); ?>:</b>
	<?php echo CHtml::encode($data->create_date); ?>
	</td>

	<td><?php echo CHtml::encode($data->getAttributeLabel('end_date')); ?>:</b>
	<?php echo CHtml::encode($data->end_date); ?>
	</td>

	<td><?php echo CHtml::encode($data->getAttributeLabel('estimate_time')); ?>:</b>
	<?php echo CHtml::encode($data->estimate_time); ?>
	</td>
	
	<td><?php echo CHtml::encode($data->getAttributeLabel('worked_time')); ?>:</b>
	<?php echo CHtml::encode($data->worked_time); ?>
	</td>

	<td><?php echo CHtml::encode($data->getAttributeLabel('resolution_id')); ?>:</b>
	<?php echo CHtml::encode($data->resolution_id); ?>
	</td>

	<td><?php echo CHtml::encode($data->getAttributeLabel('author_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->author_user_id); ?>
	</td>

	*/ ?>

</tr>