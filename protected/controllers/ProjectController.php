<?php

class ProjectController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
			array('allow',  // allow authenticated users
				'actions'=>array('index','view','SetSelected'),
				'users'=>array('@'),
			),
			array('allow', // allow coordinator
				'actions'=>array('create','update','admin','delete','addRight','removeRight','switchRight'),
				'expression'=>'User::CheckLevel(20)',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
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
		$model=new Project;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Project']))
		{
			$model->attributes=$_POST['Project'];
			if($model->save()) {
				$model->SetDefaultRights();
				$this->redirect(array('view','id'=>$model->id));
			}
		}
		$model->start_date = date("Y-m-d");
		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	public function actionSetSelected($id)
	{
		$this->loadModel($id)->SetSelected();
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

		if(isset($_POST['Project']))
		{
			$model->attributes=$_POST['Project'];
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
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Project', array(
			'criteria'=>array(
				'condition'=>'u.user_id = :uid',
				'join'=>'INNER JOIN user_has_project AS u ON u.project_id = t.id',
				'params'=>array(':uid'=>Yii::app()->user->id),
			),
		));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Project('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Project']))
			$model->attributes=$_GET['Project'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionSwitchRight($id, $user_id)
	{
		if (UserHasProject::HasUserAccess($id, Yii::app()->user->id))
		{
			UserHasProject::SwitchUserAccess($id, $user_id);
		}
		$this->redirect(array('update', 'id'=>$id));
	}
	
	public function actionRemoveRight($id, $user_id)
	{
		if (UserHasProject::HasUserAccess($id, Yii::app()->user->id))
		{
			UserHasProject::RemoveUserAccess($id, $user_id);
		}
		$this->redirect(array('update', 'id'=>$id));
	}
	
	public function actionAddRight($id, $user_id, $notification)
	{
		if (UserHasProject::HasUserAccess($id, Yii::app()->user->id))
		{
			UserHasProject::SetUserAccess($id, $user_id, $notification);
		}
		$this->redirect(array('update', 'id'=>$id));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Project the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Project::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Project $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='project-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
