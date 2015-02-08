<?php
/* @var $this UserHasProjectController */
/* @var $model UserHasProject */
/* @var $form CActiveForm */
?>
<button id="addUserHasProject">Добавить</button>
<div class="form" id="rightForm" title="Добавить">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-has-project-rightForm-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->dropDownList($model,'user_id', CHTML::listData(User::model()->findAll(), 'id', 'name')); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'project_id'); ?>
		<?php echo $form->dropDownList($model,'project_id', CHTML::listData(Project::model()->findAll(), 'id', 'name'),array('disabled'=>'true')); ?>
		<?php echo $form->error($model,'project_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'get_notifications'); ?>
		<?php echo $form->checkBox($model,'get_notifications'); ?>
		<?php echo $form->error($model,'get_notifications'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
$(function(){
	$("#addUserHasProject").click(function() {
		$("#rightForm").dialog('open');
		return false;
	});
	$("#rightForm").dialog({
		autoOpen	: false,
		modal		: true,
		//position	: { my: "left top", at: "right bottom", of: ".wf_action:last" },
		buttons		: {
			OK		: function () {
				var activeForm = $("#rightForm").find("form");
				//$.post(, activeForm.serialize(), function() {location.reload(); });
				$.get('<?php echo CHtml::normalizeUrl(array('addRight'));?>&id=' + $("#UserHasProject_project_id").val() + '&user_id=' + $("#UserHasProject_user_id").val() + '&notification=' + ($("#UserHasProject_get_notifications").prop("checked") ? '1' : '0'), '', function() {location.reload(); });
			},
			Cancel	: function () {$("#rightForm").dialog("close");}
		}
	});
});
</script>