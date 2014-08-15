<?php
$actions = $model->getWorkflowActions();
foreach ($actions as $action) {
	echo CHtml::link(
		$action->button_name,
		array('ticket/makeWF',
			'id'=>$model->id,
			'action'=>$action->step_name),
		array(
			'class'=>'wf_action'.
				(strpos($action->input_data, 'resolution') !== false ?' wf_resolution' : '').
				(strpos($action->input_data, 'comment') !== false ?' wf_comment' : '')
			)
	);
}?>
<div class="form" id="resolution" title="Уточните">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ticket-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php
		echo $form->labelEx($model,'resolution_id', array('class'=>'wfc_resolution'));
		echo $form->dropDownList($model,'resolution_id', CHTML::listData(Resolution::model()->findAll('id > 1'), 'id', 'name'), array('class'=>'wfc_resolution'));
		echo $form->error($model,'resolution_id');
		echo $form->label($model,'worked_time', array('class'=>'wfc_resolution'));
		echo $form->textField($model, 'worked_time', array('class'=>'wfc_resolution'));
		$comment = new Comment();
		echo $form->label($comment,'text', array('class'=>'wfc_comment'));
		echo $form->textArea($comment,'text',array('maxlength'=>1000, 'class'=>'wfc_comment'));
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
$(function () {
	$(".wf_action").button().click(function (ev, ui){
		var action = $(ev.target).closest(".wf_action");
		if (action.hasClass("wf_resolution") || action.hasClass("wf_comment")) {
			if (action.hasClass("wf_resolution")) $(".wfc_resolution").show();
			else $(".wfc_resolution").hide();
			if (action.hasClass("wf_comment")) $(".wfc_comment").show();
			else $(".wfc_comment").hide();
			ev.preventDefault();
			//TODO make action
			$("#resolution").find("form").attr("action", action.attr("href"));
			$("#resolution").dialog('open');
		}
	});
	$("#resolution").dialog({
		autoOpen	: false,
		modal		: true,
		position	: { my: "left top", at: "right bottom", of: ".wf_action:last" },
		buttons		: {
			OK		: function () {
				var activeForm = $("#resolution").find("form");
				$.post(activeForm.get(0).action, activeForm.serialize(), function() {location.reload(); });
			},
			Cancel	: function () {$("#resolution").dialog("close");}
		}
	});
});
</script>