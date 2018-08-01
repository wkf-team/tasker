<?php
	$this->renderPartial("display_status", ['model'=>$model]);
	echo CHtml::link($model->id . ". " . $model->subject, $this->createAbsoluteUrl('ticket/view', ['id'=>$model->id]));
?>