<?php
	$this->renderPartial("display_status", ['model'=>$model]);
	echo CHtml::link($model->subject, ['ticket/view', 'id'=>$model->id]);
?>