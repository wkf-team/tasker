<?php

class TW_InnerTaskList extends CWidget {
	public $ticket_id;
	
    public function run() {
		$dataProvider = new CActiveDataProvider('Ticket', array('criteria'=>array(
						// открытые цели, незакрытые задач
						'condition'=>'parent_ticket_id = :tid',
						'params'=>array(':tid'=>$this->ticket_id),
						'order'=>Ticket::$orderString,
					),
					'pagination' => array('pageSize'=>30)));
		if ($dataProvider->totalItemCount > 0) {
			$this->render('TWUI_ticketList', array(
				'dataProvider'=>$dataProvider,
				'noChildren'=>false,
			));
		}
    }
 
}
?>