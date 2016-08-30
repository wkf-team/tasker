<?php
class RW_GoalsComplete extends CWidget {
 
    public $goals;
	public $current_project;
 
    public function run() {
		$this->goals = VGoalsComplete::model()->findAll(array(
			'condition'=>'p.user_id = :uid AND pr.is_active = 1'.($this->current_project && Project::GetSelected() ? " AND pr.id = ".Project::GetSelected()->id : ""),
			'join'=>'INNER JOIN ticket AS tc ON t.id = tc.id
					INNER JOIN user_has_project AS p ON p.project_id = tc.project_id
					INNER JOIN project AS pr ON p.project_id = pr.id',
			'params'=>array(':uid'=>Yii::app()->user->id),
		));
        $this->render('RWUI_goalsComplete');
    }
 
}
?>