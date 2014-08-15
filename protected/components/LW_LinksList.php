<?php
global $yii_path;
include_once ($yii_path."zii/widgets/CPortlet.php");

class LW_LinksList extends CPortlet {
	public $ticket_id;
	
    public function renderContent() {
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