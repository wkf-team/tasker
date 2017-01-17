<?php

class ApiController extends Controller
{
	public function actionTicket($id)
	{
		$model = $this->loadModel($id);
		$this->renderPartial('ticket',[
			'model'=>$model
		]);
	}

	public function actionTicket_html_long($id)
	{
		$model = $this->loadModel($id);
		$this->renderPartial('ticket_html_long',[
			'model'=>$model
		]);
	}

	public function actionTicket_html_short($id)
	{
		$model = $this->loadModel($id);
		$this->renderPartial('ticket_html_short',[
			'model'=>$model
		]);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Ticket::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}