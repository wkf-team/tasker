<?php
class TW_TicketList extends CWidget {
	public $dataProvider;
	
    public function run() {
		$this->render('TWUI_ticketList',array(
			'dataProvider'=>$this->dataProvider,
		));
    }
 
}
?>