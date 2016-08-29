<?php
/* @var $this TicketController */
/* @var $data Ticket */
?>

<tr class="plan-ticket-row">
	<td>
		<button class="btnQEdit">Quick edit</button>
		<?php
		echo CHtml::ajaxLink("Delete", array("ticket/delete", "id" => $data->id), 
			array("type" => "POST"), 
			array("class" => "btnDelete",
				"onclick" =>
				"if (!confirm('Удалить задачу?')) event.preventDefault();
				else setTimeout(function () {location.reload();}, 100);"));
		?>
		<?php if ($data->ticket_type_id != 4) {?>
			<button class="btnAdd">Add</button>
		<?php } ?>
	</td>
	<td>
		<!-- Offset -->
		<?php
		if ($offset >= 2) {
			for ($i = 1; $i < $offset; $i++) {
				?>
				<span class="ui-icon ui-icon-blank" style="display:inline-block;"></span>
				<?
			}
		}
		if ($offset >= 1) {
			?>
			<span class="ui-icon ui-icon-carat-1-sw" style="display:inline-block;"></span>
			<?
		}
		?>
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
		echo CHtml::link(CHtml::encode($data->id.". ".$data->subject), array('view', 'id'=>$data->id));
		$data->includeBlockedBy = true;
		echo CHtml::hiddenField("data", CJSON::encode($data));
		?>
	</td>
	<td><?php 
		//if ($data->ticket_type_id == 1)	echo CHtml::encode($data->due_date);
		//else
		echo $data->ownerUser ? CHtml::encode($data->ownerUser->name) : "Not set";
	?></td>
	<td class="filter_balance_only"><?php echo $data->encodeDate($data->estimate_start_date); ?></td>
	<td class="filter_balance_only"><?php echo $data->encodeDate($data->due_date); ?></td>
	<td class="filter_balance_only"><?php echo CHtml::encode($data->estimate_time); ?></td>
	<td><?php echo $data->GetBlockedBy_HtmlString(); ?></td>
</tr>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>new CActiveDataProvider('Ticket', [
		'criteria'=>[
			'condition'=>'parent_ticket_id = '.$data->id
		],
	]),
	'itemView'=>'application.views.ticket._viewPlan',
	'emptyText'=>'',
	'summaryText'=>'',
	'viewData'=>['offset'=>$offset + 1]
)); ?>