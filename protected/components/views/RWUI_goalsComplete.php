<div id="goalsComplete" style="display:inline-block;padding-right:10px;">
Выполнение целей по проектам:<br/>
    <?php 
    foreach($this->goals as $goal) {
        echo CHtml::link($goal->subject, array('ticket/view', 'id'=>$goal->id));
		if ($goal->total > 0) {
			echo ": " . (int)($goal->closed / $goal->total * 100) . "% (" . $goal->closed . " из ";
			echo CHtml::link($goal->total, array('ticket/EpicTasks', 'id'=>$goal->id)). ")";
		}
		echo "<br/>";
    }
    ?>
</div>