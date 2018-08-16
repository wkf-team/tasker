<div class="span-auto ticket-details">
<h2>Даты</h2>
<?php

$this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'cssFile' => 'css/grid.css',
    'attributes'=>array(
        array(
            'label' => CHtml::activeLabel($model, "create_date"),
            'value' => $model->encodeDate($model->create_date)
        ),
        array(
            'label' => CHtml::activeLabel($model, "estimate_start_date"),
            'value' => $model->encodeDate($model->estimate_start_date)
        ),
        array(
            'label' => CHtml::activeLabel($model, "due_date"),
            'value' => $model->encodeDate($model->due_date)
        ),
    ),
)); 
?>
<h2>Время</h2>
<?php

$this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'cssFile' => 'css/grid.css',
    'attributes'=>array(
        'story_points',
        'estimate_time',
        'worked_time',
    ),
)); 
?>
</div>