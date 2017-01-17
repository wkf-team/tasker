<?php
	$data = [
		'id' => $model->id,
		'title' => $model->subject,
		'status_id' => $model->status_id,
		'status' => CHtml::encode($model->status->name)
	];
	echo CJSON::encode($data);
?>