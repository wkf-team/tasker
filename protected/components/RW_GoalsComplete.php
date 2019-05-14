<?php
class RW_GoalsComplete extends CWidget {
 
    public $goals;
	public $current_project;
 
    public function run() {
		$p = Project::GetSelected();
		if (!$p) return;
		$this->current_project = $p->id;
		$this->goals = VGoalsComplete::model()->findAll(array(
			'condition'=>'project_id = :pid',
            'order' => 'due_date',
			'params'=>array(':pid'=>$this->current_project),
		));
        $this->render('RWUI_goalsComplete');
    }
 
}
?>