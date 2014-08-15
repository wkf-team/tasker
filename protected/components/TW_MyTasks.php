<?php
class TW_MyTasks extends CWidget {
 
    public function run() {
		$id = Yii::app()->user->id;
		if (!$id)
		{
			echo "Список задач пуст";
			return;
		}
		$dataProvider=new CActiveDataProvider('Ticket', array(
			'criteria'=>array(
				// открытые цели, незакрытые задач
				'condition'=>'(status_id < 6 OR status_id = 6 AND end_date > SUBDATE(NOW(), 14)) AND (owner_user_id = '.$id.' OR responsible_user_id = '.$id.')',
				'order'=>Ticket::$orderString,
			),'pagination'=>array(
				'pageSize'=>20,
			),
		));
		$this->render('TWUI_ticketList',array(
			'dataProvider'=>$dataProvider,
		));
    }
 
}
?>