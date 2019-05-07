<?php
/* @var $this IterationController */
/* @var $dataProvider CActiveDataProvider */
/* @var $title string */

if (!isset($title)) {
    $title = 'Текущая итерация';
}

$this->breadcrumbs=array(
	$title,
);

$this->menu=array(
	array('label'=>'Create Iteration', 'url'=>array('create')),
);
?>

<h1><?=$title ?></h1>
<?php if ($model) { ?>
<div>
	<div class="row">
		<div class="span-10">
			</br/>
			<?= $model->getLabel(); ?>
		</div>
		<div class="span-5 last">
			<?php
			if ($model->status_id == 1) {
				echo CHtml::link("Start", ['start', 'id'=>$model->id], ['class'=>'control-buttons']);
			} else {
				echo CHtml::link("Roll-up", ['rollup', 'id'=>$model->id], ['class'=>'control-buttons']);
			}
			?>
		</div>
		<script>
			$(function () {
				$(".control-buttons").button();
			});
		</script>
	</div>
	<div class="row">
		<hr class="space"/>
		<? $this->renderPartial('_viewWithTickets', ['model'=>$model]); ?>
	</div>
</div>
<? } ?>