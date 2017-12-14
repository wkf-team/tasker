<?php
class TW_TicketList extends CWidget {
	public $dataProvider;
	public $noChildren;
	public $allChildren;
	
    public function run() {
		$this->render('TWUI_ticketList',array(
			'dataProvider'=>$this->dataProvider,
			'noChildren'=>$this->noChildren,
			'allChildren'=>$this->allChildren,
		));
    }
 
}
?>