<?php
	$this->renderPartial("display_status", ['model'=>$model]);
	echo CHtml::link($model->id, $this->createAbsoluteUrl('ticket/view', ['id'=>$model->id]), ['title'=>$model->subject]);
?>