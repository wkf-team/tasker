<?php
global $yii_path;
include_once ($yii_path."zii/widgets/CPortlet.php");

class CW_CommentList extends CPortlet {
	public $ticket_id;
	
    public function renderContent() {
		$this->render('CWUI_commentList',array(
			'dataProvider'=>new CActiveDataProvider('Comment', array('criteria'=>array(
				'condition'=>'ticket_id = :tid',
				'params'=>array(':tid'=>$this->ticket_id),
				'order'=>'create_date DESC',
			),
			'pagination' => array('pageSize'=>30))),
			'ticket_id'=>$this->ticket_id
		));
    }
 
}
?>