<?php
class RW_GoalsComplete extends CWidget {
 
    public $goals;
	public $current_project;
 
    public function run() {
		$this->current_project = Project::GetSelected()->id;
		$this->goals = VGoalsComplete::model()->findAll(array(
			'condition'=>'project_id = :pid',
			'params'=>array(':pid'=>$this->current_project),
		));
        $this->render('RWUI_goalsComplete');
    }
 
}
?>