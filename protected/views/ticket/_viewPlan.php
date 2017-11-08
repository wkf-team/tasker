<?php
/* @var $this TicketController */
/* @var $data Ticket */

if (!isset($noChildren)) $noChildren = false;
$expanded_class = "";
$t = $data;
$i = 0;
while($t->parent_ticket_id != null) {
	$expanded_class .= "ticket-".$t->parent_ticket_id."-expanded ";
	$t = $t->parentTicket;
}

if ($filterForBacklog && $data->ticket_type_id > 2 && $data->parent_ticket_id != null) ;
else {
?>
<? if (!$noChildren && ($data->ticket_type_id <= 2 || $data->parent_ticket_id == null)) { ?>
	<tr><td colspan='7'><span style='border-top:1px dotted; display:flex;'></span></td></tr>
<? } ?>
<tr class="plan-ticket-row <?=$expanded_class?>">
	<td>
		<a href="#" class="btnQEdit">Quick edit</a>
		<?php
		echo CHtml::ajaxLink("Delete", array("ticket/delete", "id" => $data->id), 
			array("type" => "POST"), 
			array("class" => "btnDelete",
				"onclick" =>
				"if (!confirm('Удалить задачу?')) event.preventDefault();
				else setTimeout(function () {location.reload();}, 100);"));
		?>
		<?php
		/*if (!$noChildren) {
			echo CHtml::ajaxLink("Более приоритетно",
			["ticket/setPriority", 'id'=>$data->id, 'move'=>'up'], [
				'success'=>'setTimeout(function () {location.reload(); }, 100)'
			],[
				'class'=>'btnMorePriority',
			])."\n";
			echo CHtml::ajaxLink("Менее приоритетно",
			["ticket/setPriority", 'id'=>$data->id, 'move'=>'down'], [
				'success'=>'setTimeout(function () {location.reload(); }, 100)'
			],[
				'class'=>'btnLessPriority',
			])."\n";
		}*/
		if ($filterForBacklog) {
			if ($data->iteration_id == $iteration_id) {
				echo CHtml::ajaxLink("Исключить из текущей итерации",
				["ticket/setIteration", 'id'=>$data->id, 'iteration_id'=>-1], [
					'success'=>'setTimeout(function () {location.reload(); }, 100)'
				],[
					'class'=>'btnRemoveFromIteration',
				]);
			} else {
				echo CHtml::ajaxLink("Включить в итерацию",
				["ticket/setIteration", 'id'=>$data->id, 'iteration_id'=>$iteration_id], [
					'success'=>'setTimeout(function () {location.reload(); }, 100)'
				],[
					'class'=>'btnAddToIteration',
				]);
			}
		}
		?>
		<? if ($data->ticket_type_id < ($filterForBacklog == true ? 2 : 4)) {?>
			<a href="#" class="btnAdd">Add</a>
		<? } ?>
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
				case 4: echo "ui-icon-red ui-icon-notice"; break;
			}
		?>" style="display:inline-block;"></span>
		<!-- id and subject -->
		<?
		echo CHtml::link(CHtml::encode($data->id.". ".$data->subject), array('ticket/view', 'id'=>$data->id));
		$data->includeBlockedBy = true;
		echo CHtml::hiddenField("data", CJSON::encode($data));
		?>
		<!-- show / hide children -->
		<? if ((!isset($noChildren) || !$noChildren) && count($data->tickets) > 0) { ?>
			<span class="ui-icon ui-icon-triangle-1-s ticket-<?=$data->id?>-expanded" style="display:inline-block;" onclick="collapse(<?=$data->id?>);"></span>
			<span class="ui-icon ui-icon-triangle-1-e ticket-<?=$data->id?>-collapsed ticket-collapsed" style="display:inline-block;" onclick="expand(<?=$data->id?>);"></span>
		<? } ?>
	</td>
	<td><?php echo CHtml::encode($data->status->name); ?></td>
	<td><?php echo CHtml::encode($data->priority->name); ?></td>
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
if (!$noChildren) {
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>new CActiveDataProvider('Ticket', [
		'criteria'=>[
			'condition'=>'parent_ticket_id = '.$data->id.' AND status_id <> 7',
			'order'=>Ticket::$orderString,
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