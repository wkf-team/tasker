<div class="span-auto">
<h2>Пользователи</h2>
</div>
<div class="span-auto ticket-details">
<?php

$this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'cssFile' => 'css/grid.css',
    'attributes'=>array(
        'ownerUser.name:text:'.CHtml::activeLabel($model, "owner_user_id"),
        'authorUser.name:text:'.CHtml::activeLabel($model, "author_user_id"),
        'testerUser.name:text:'.CHtml::activeLabel($model, "tester_user_id"),
        'responsibleUser.name:text:'.CHtml::activeLabel($model, "responsible_user_id"),
    ),
)); 
?>
</div>