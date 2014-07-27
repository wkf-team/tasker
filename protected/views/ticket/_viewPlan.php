<?php
/* @var $this TicketController */
/* @var $data Ticket */
?>

<tr>
	<td>
		<button class="btnQEdit">Quick edit</button>
		<?php 
		//echo CHtml::link("Open", array("ticket/view", "id" => $data->id), array("class" => "btnOpen"));
		echo CHtml::ajaxLink("Delete", array("ticket/delete", "id" => $data->id), 
			array("type" => "POST"), 
			array("class" => "btnDelete",
				"onclick" =>
				"if (!confirm('Удалить задачу?')) event.preventDefault();
				else setTimeout(function () {location.reload();}, 100);"))?>
		<?php if ($data->ticket_type_id == 1) {?>
			<button class="btnAdd">Add</button>
			<!--<button class="btnInline">Inline</button>-->
		<?php } ?>
	</td>
	<td><?php
		echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id));
		$data->includeBlockedBy = true;
		echo CHtml::hiddenField("data", CJSON::encode($data));
	?></td>
	<td><?php 
		if ($data->ticket_type_id != 1) {
			echo "<span class='ui-icon ".($data->parent_ticket_id == null ? "ui-icon-notice" : "ui-icon-carat-1-sw")."' style='display:inline-block;'></span>";
		}
		echo CHtml::link(CHtml::encode($data->subject), array('view', 'id'=>$data->id));
	?></td>
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