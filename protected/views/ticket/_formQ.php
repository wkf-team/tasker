<?php
/* @var $this TicketController */
/* @var $model Ticket */
/* @var $form CActiveForm */
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ajax-form',
	'action' => array('Ticket/AjaxEdit'),
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля со <span class="required">*</span> обязательны.</p>

	<?php
		echo $form->errorSummary($model); 
		echo $form->hiddenField($model, 'id');
		echo $form->hiddenField($model, 'parent_ticket_id');
		echo $form->hiddenField($model, 'ticket_type_id');
	?>

	
	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>20,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'subject'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'due_date'); ?>
		<?php echo $form->dateField($model,'due_date'); ?>
		<?php echo $form->error($model,'due_date'); ?>
	</div>

	<div class="row filter_balance_only">
		<?php echo $form->labelEx($model,'estimate_time'); ?>
		<?php //TODO: switch to timeField
		echo $form->textField($model,'estimate_time'); ?>
		<?php echo $form->error($model,'estimate_time'); ?>
	</div>

	<div class="row filter_balance_only">
		<?php echo $form->labelEx($model,'priority_id'); ?>
		<?php echo $form->dropDownList($model,'priority_id', CHTML::listData(Priority::model()->findAll(), 'id', 'name')); ?>
		<?php echo $form->error($model,'priority_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'owner_user_id'); ?>
		<?php echo $form->dropDownList($model,'owner_user_id', CHTML::listData(User::model()->findAll(), 'id', 'name')); ?>
		<?php echo $form->error($model,'owner_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tester_user_id'); ?>
		<?php echo $form->dropDownList($model,'tester_user_id', CHTML::listData(User::model()->findAll(), 'id', 'name'), array('empty' => '-- Не выбрано --')); ?>
		<?php echo $form->error($model,'tester_user_id'); ?>
	</div>

	<div class="row">
		<?php
		echo CHtml::label("Предшественники", "blocked_by"); 
		echo CHtml::textField("blocked_by", $model->GetBlockedBy_ValueString());
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->