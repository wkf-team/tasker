<style>
    .progress-bar {
        width:120px;
        height:15px;
        background: lightgrey;
        display:inline-block;
    }
    .progress-item {
        height:15px;
        float:left;
    }
    .progress-done {
        background: #0f0;
    }
    .progress-current {
        background: yellow;
    }
    .progress-ontrack {
        background: cyan;
    }
    .progress-delayed {
        background: red;
    }
</style>

<div id="goalsComplete" style="display:inline-block;padding-right:10px;">
Выполнение целей по проектам:<br/>
    <?php 
    foreach($this->goals as $goal) {
		if ($goal->total > 0) {
            $completed = (int)($goal->closed / $goal->total * 100);
            $progress = (int)($goal->progress / $goal->total * 100);
            $text = $completed . "% (" . $goal->closed. " из " . $goal->total . ")" .
                    ($goal->due_date ? " до ".$goal->encodeDate($goal->due_date) : "");
            if ($goal->due_date && ($goal->due_date != $goal->start_date)) {
                $date = date_parse($goal->start_date);
                $start_date = new DateTime();
                $start_date->setDate($date['year'], $date['month'], $date['day']);
                $date = date_parse($goal->due_date);
                $due_date = new DateTime();
                $due_date->setDate($date['year'], $date['month'], $date['day']);
                $today = new DateTime();
                $days_total = date_diff($due_date, $start_date, true)->days;
                $days_past = $today > $start_date ? date_diff($today, $start_date, true)->days : 0;
                if ($days_past / $days_total > ($goal->closed + $goal->progress) / $goal->total) {
                    $rest_state = "delayed";
                    $text .= " НАРУШЕН";
                } else {
                    $rest_state = "ontrack";
                    $text .= " по плану";
                }
            } else {
                $rest_state = "ontrack";
                $text .= " по плану";
            }
            ?>

<div class="progress-bar" title="<?= $text ?>">
    <div class="progress-item progress-done" style="width:<?= $completed ?>%;"></div>
    <div class="progress-item progress-current" style="width:<?= $progress ?>%;"></div>
    <div class="progress-item progress-<?= $rest_state ?>" style="width:<?= 100 - $completed - $progress ?>%;"></div>
</div>

            <?php
		}
        echo CHtml::link($goal->subject, array('ticket/view', 'id'=>$goal->id));
		echo "<br/>";
    }
    ?>
</div>