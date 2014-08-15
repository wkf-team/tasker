<?php
class RW_GoalsComplete extends CWidget {
 
    public $goals;
 
    public function run() {
		$this->goals = VGoalsComplete::model()->findAll();
        $this->render('RWUI_goalsComplete');
    }
 
}
?>