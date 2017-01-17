<?php
	$this->renderPartial("display_status", ['model'=>$model]);
	echo CHtml::link($model->id, ['ticket/view', 'id'=>$model->id], ['title'=>$model->subject]);
?>