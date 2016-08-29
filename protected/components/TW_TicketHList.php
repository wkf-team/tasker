<?php
class TW_TicketHList extends CWidget {
	public $dataProvider;
	
    public function run() {
		$model=Ticket::create();
		
		$this->render('TWUI_ticketHList',array(
			'dataProvider'=>$this->dataProvider,
			'model'=>$model,
		));
    }
 
}
?>