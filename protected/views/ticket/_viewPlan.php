<?php
/* @var $this TicketController */
/* @var $data Ticket */

if ($filterForBacklog && $data->ticket_type_id > 2 && $data->parent_ticket_id != null) ;
else {
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
		<? if ($data->ticket_type_id != 4) {?>
			<button class="btnAdd">Add</button>
		<? } ?>
		<?php
		if ($filterForBacklog) {
			if ($data->iteration_id == $iteration_id) {
				echo CHtml::ajaxLink("Исключить из текущей итерации",
				["ticket/setIteration", 'id'=>$data->id, 'iteration_id'=>-1], [
					'success'=>'location.reload()'
				],[
					'class'=>'btnRemoveFromIteration',
				]);
			} else {
				echo CHtml::ajaxLink("Включить в итерацию",
				["ticket/setIteration", 'id'=>$data->id, 'iteration_id'=>$iteration_id], [
					'success'=>'location.reload()'
				],[
					'class'=>'btnAddToIteration',
				]);
			}
		}
		?>
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
		echo CHtml::link(CHtml::encode($data->id.". ".$data->subject), array('ticket/view', 'id'=>$data->id));
		$data->includeBlockedBy = true;
		echo CHtml::hiddenField("data", CJSON::encode($data));
		?>
	</td>
	<td><?php echo CHtml::encode($data->status->name); ?></td>
	<td><?php echo $data->GetBlockedBy_HtmlString(); ?></td>
	<td><?= $data->ownerUser ? CHtml::encode($data->ownerUser->name) : "Not set"; ?></td>
	<td><?php
		if ($data->ticket_type_id == 2) {
			if ($data->story_points > 0) echo CHtml::encode($data->story_points)." SP";
		} else {
			if ($data->estimate_time > 0) echo CHtml::encode($data->estimate_time)." ч";
		}
		?>
	</td>
</tr>
<?php 
if (!isset($noChildren) || !$noChildren) {
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>new CActiveDataProvider('Ticket', [
		'criteria'=>[
			'condition'=>'parent_ticket_id = '.$data->id.' AND status_id <> 7'
		],
		'pagination'=>false,
	]),
	'itemView'=>'application.views.ticket._viewPlan',
	'emptyText'=>'',
	'summaryText'=>'',
	'viewData'=>['offset'=>$offset + 1, 'filterForBacklog'=>$filterForBacklog, 'iteration_id'=>$iteration_id]
));
}
?>

<? }// end if filterForBacklog ?>