<?php
class RW_GoalsComplete extends CWidget {
 
    public $goals;
 
    public function run() {
		$this->goals = VGoalsComplete::model()->findAll(array(
			'condition'=>'p.user_id = :uid',
			'join'=>'INNER JOIN ticket AS tc ON t.id = tc.id
					INNER JOIN user_has_project AS p ON p.project_id = tc.project_id',
			'params'=>array(':uid'=>Yii::app()->user->id),
		));
        $this->render('RWUI_goalsComplete');
    }
 
}
?>