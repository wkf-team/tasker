<?php

class IterationController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $historyItemsProvider;
	public $historyItemsView;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow authenticated user 
				'actions'=>array('view'),
				'users'=>array('@'),
			),
			array('allow', // allow participants
				'actions'=>array('index'),
				'expression'=>'User::CheckLevel(10)',
			),
			array('allow', // allow coordinator
				'actions'=>array('admin','create','update','delete','start','rollup'),
				'expression'=>'User::CheckLevel(20)',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionStart($id)
	{
		if ($this->loadModel($id)->start())
			$this->redirect(array('index'));
	}
	
	public function actionRollup($id)
	{
		$model = $this->loadModel($id);
		
		if ($model->rollup())
			$this->redirect(array('index'));
		else
			echo CJSON::encode($model->errors);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->SetHistory();
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Iteration;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Iteration']))
		{
			$model->attributes=$_POST['Iteration'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Iteration']))
		{
			$model->attributes=$_POST['Iteration'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		if (Project::GetSelected()) {
			$model = Iteration::model()->find([
				'condition'=>'project_id = '.Project::GetSelected()->id.' AND status_id < 6',
				'order'=>'due_date ASC'
			]);
			$this->SetHistory();
		}
		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Iteration('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Iteration']))
			$model->attributes=$_GET['Iteration'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	private function SetHistory() {
		if (Project::GetSelected()) {
			$this->historyItemsProvider = new CActiveDataProvider('Iteration', ['criteria'=>[
				'condition'=>'project_id = '.Project::GetSelected()->id,
				'order'=>'due_date DESC',
			]]);
			$this->historyItemsView = "application.views.iteration._view";
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Iteration::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='iteration-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}