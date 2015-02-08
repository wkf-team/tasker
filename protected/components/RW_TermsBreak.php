<?php
class RW_TermsBreak extends CWidget {
 
    public $breaks;
 
    public function run() {
		$this->breaks = VTermsBreak::model()->findAll(array(
			'condition'=>'p.user_id = :uid',
			'join'=>'INNER JOIN ticket AS tc ON t.ticket_id = tc.id
					INNER JOIN user_has_project AS p ON p.project_id = tc.project_id',
			'params'=>array(':uid'=>Yii::app()->user->id),
		));
        $this->render('RWUI_termsBreak');
    }
 
}
?>