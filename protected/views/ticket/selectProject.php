<?php
/* @var $this TicketController */
/* @var $model Ticket */
/* @var $form CActiveForm */

$this->breadcrumbs=array(
	'Бэклог'=>array('iteration/index'),
	$model->id=>array('view','id'=>$model->id),
	'Перенести',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотреть', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Поиск', 'url'=>array('admin')),
);
?>

<h1>Перенести <?php echo $model->ticketType->name."-".$model->id; ?> в другой проект</h1>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'select-project-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'project_id'); ?>
		<?php echo $form->dropDownList($model,'project_id', CHTML::listData(Project::model()->findAll(array(
				'condition'=>'u.user_id = :uid',
				'join'=>'INNER JOIN user_has_project AS u ON u.project_id = t.id',
				'params'=>array(':uid'=>Yii::app()->user->id),
			)), 'id', 'name')); ?>
		<?php echo $form->error($model,'project_id'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton("Изменить"); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->