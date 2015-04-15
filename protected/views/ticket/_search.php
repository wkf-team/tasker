<?php
/* @var $this TicketController */
/* @var $model Ticket */
/* @var $form CActiveForm */
?>
<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>1023)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_date'); ?>
		<?php echo $form->textField($model,'create_date'); ?>
		<a class="today_link" href="#">Сегодня</a>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'estimate_start_date'); ?>
		<?php echo $form->textField($model,'estimate_start_date'); ?>
		<a class="today_link" href="#">Сегодня</a>
	</div>

	<div class="row">
		<?php echo $form->label($model,'due_date'); ?>
		<?php echo $form->textField($model,'due_date'); ?>
		<a class="today_link" href="#">Сегодня</a>
	</div>

	<div class="row">
		<?php echo $form->label($model,'end_date'); ?>
		<?php echo $form->textField($model,'end_date'); ?>
		<a class="today_link" href="#">Сегодня</a>
	</div>

	<div class="row">
		<?php echo $form->label($model,'estimate_time'); ?>
		<?php echo $form->textField($model,'estimate_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'worked_time'); ?>
		<?php echo $form->textField($model,'worked_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'priority_id'); ?>
		<?php echo $form->dropDownList($model,'priority_id', CHTML::listData(Priority::model()->findAll(), 'id', 'name'), array('empty' => '-- Выберите для поиска --')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status_id'); ?>
		<?php echo $form->dropDownList($model,'status_id', CHTML::listData(Status::model()->findAll(), 'id', 'name'), array('empty' => '-- Выберите для поиска --')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'resolution_id'); ?>
		<?php echo $form->dropDownList($model,'resolution_id', CHTML::listData(Resolution::model()->findAll(), 'id', 'name'), array('empty' => '-- Выберите для поиска --')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ticket_type_id'); ?>
		<?php echo $form->dropDownList($model,'ticket_type_id', CHTML::listData(TicketType::model()->findAll(), 'id', 'name'), array('empty' => '-- Выберите для поиска --')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'author_user_id'); ?>
		<?php echo $form->dropDownList($model,'author_user_id', CHTML::listData(User::model()->findAll(), 'id', 'name'), array('empty' => '-- Выберите для поиска --')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'owner_user_id'); ?>
		<?php echo $form->dropDownList($model,'owner_user_id', CHTML::listData(User::model()->findAll(), 'id', 'name'), array('empty' => '-- Выберите для поиска --')); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'tester_user_id'); ?>
		<?php echo $form->dropDownList($model,'tester_user_id', CHTML::listData(User::model()->findAll(), 'id', 'name'), array('empty' => '-- Выберите для поиска --')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'responsible_user_id'); ?>
		<?php echo $form->dropDownList($model,'responsible_user_id', CHTML::listData(User::model()->findAll(), 'id', 'name'), array('empty' => '-- Выберите для поиска --')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'parent_ticket_id'); ?>
		<?php echo $form->dropDownList($model,'parent_ticket_id', CHTML::listData(Ticket::model()->findAll('ticket_type_id = 1 AND status_id < 3'), 'id', 'subject'), array('empty' => '-- Выберите для поиска --')); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'project_id'); ?>
		<?php echo $form->dropDownList($model,'project_id', CHTML::listData(Project::model()->findAll(array(
			'join' => 'INNER JOIN user_has_project AS p ON t.id = p.project_id',
			'condition' => 'p.user_id = :uid',
			'params' => array(':uid'=>Yii::app()->user->id)
		)), 'id', 'name'), array('empty' => '-- Выберите для поиска --')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'initial_version'); ?>
		<?php echo $form->textField($model,'initial_version',array('size'=>25,'maxlength'=>25)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'resolved_version'); ?>
		<?php echo $form->textField($model,'resolved_version',array('size'=>25,'maxlength'=>25)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Поиск'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
<script>
$(function() {
	$(".today_link").click(function(ev) {
		ev.preventDefault();
		var date = new Date();
		var values = [ date.getDate(), date.getMonth() + 1 ];
		for( var id in values ) {
		  values[ id ] = values[ id ].toString().replace( /^([0-9])$/, '0$1' );
		}
		$(ev.target).closest("div").find("input").val(date.getFullYear()+'-'+values[1]+'-'+values[0]);
	});
});
</script>