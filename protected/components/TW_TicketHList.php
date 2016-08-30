<?php
class TW_TicketHList extends CWidget {
	public $dataProvider;
	public $filterForBacklog;
	public $showFooterButtons;
	public $iteration_id;
	
	public function init() {
		if (!$this->dataProvider) {
			$this->dataProvider=new CActiveDataProvider('Ticket', array(
				'criteria'=>array(
					// открытые топ-левел задачи
					'condition'=>
						'status_id < 6 AND p.user_id = :uid AND p.is_selected = 1 '.
						'AND (parent_ticket_id IS NULL)',
					'join'=>'INNER JOIN user_has_project AS p ON p.project_id = t.project_id',
					'params'=>array(':uid'=>Yii::app()->user->id),
				),'pagination'=>false,
			));
		}
	}
	
    public function run() {
		$model=Ticket::create();
		
		$this->render('TWUI_ticketHList',array(
			'dataProvider'=>$this->dataProvider,
			'model'=>$model,
			'filterForBacklog'=>$this->filterForBacklog,
			'showFooterButtons'=>$this->showFooterButtons,
			'iteration_id'=>$this->iteration_id,
		));
    }
 
}
?>