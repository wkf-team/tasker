<?php

class AW_AttList extends CWidget {
	public $ticket_id;
	
    public function run() {
		$this->render('AWUI_attList',array(
			'dataProvider'=>new CActiveDataProvider('Attachment', array('criteria'=>array(
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