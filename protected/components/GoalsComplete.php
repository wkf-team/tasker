<?php
class GoalsComplete extends CWidget {
 
    public $goals;
 
    public function run() {
		$this->goals = VGoalsComplete::model()->findAll();
        $this->render('goalsComplete');
    }
 
}
?>