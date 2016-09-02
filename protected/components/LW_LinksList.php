<?php

class LW_LinksList extends CWidget {
	public $ticket_id;
	
    public function run() {
		$fromProvider = new CActiveDataProvider('Relation', array('criteria'=>array(
			'condition'=>'ticket_from_id = :tid',
			'params'=>array(':tid'=>$this->ticket_id),
			'order'=>'relation_type_id, ticket_to_id',
		)));
		$toProvider = new CActiveDataProvider('Relation', array('criteria'=>array(
			'condition'=>'ticket_to_id = :tid',
			'params'=>array(':tid'=>$this->ticket_id),
			'order'=>'relation_type_id, ticket_from_id',
		)));
		$this->render('LWUI_linksList',array(
			'fromProvider'=>$fromProvider,
			'toProvider'=>$toProvider,
			'ticket_id'=>$this->ticket_id
		));
    }
 
}
?>