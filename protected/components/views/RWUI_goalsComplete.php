<div id="goalsComplete" style="display:inline-block;padding-right:10px;">
Выполнение целей по проектам:<br/>
    <?php 
    foreach($this->goals as $goal) {
        echo CHtml::link($goal->subject, array('ticket/view', 'id'=>$goal->id));
		if ($goal->total > 0) {
			echo ": " . (int)($goal->closed / $goal->total * 100) . "% (";
			echo CHtml::link($goal->closed. " из " . $goal->total, array('ticket/EpicTasks', 'id'=>$goal->id));
			echo ")" . ($goal->due_date ? " до ".$goal->encodeDate($goal->due_date) : "");
		}
		echo "<br/>";
    }
    ?>
</div>