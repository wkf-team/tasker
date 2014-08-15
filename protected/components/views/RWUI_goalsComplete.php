<div id="goalsComplete">
Выполнение целей:<br/>
    <?php 
    foreach($this->goals as $goal) {
        echo CHtml::link($goal->subject, array('ticket/view', 'id'=>$goal->id));
		echo ": " . $goal->closed . " из ";
		echo CHtml::link($goal->total, array('ticket/EpicTasks', 'id'=>$goal->id)). "<br/>";
    }
    ?>
</div>