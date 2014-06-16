<?php

class TicketController extends Controller
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
			array('allow',  // allow all users
				'actions'=>array('index','view','usersTasks','epicTasks','QuickSearch'),
				'users'=>array('*'),
			),
			array('allow', // allow for participants
				'actions'=>array('create','update','admin','plan','AjaxEdit','makeWF'),
				'expression'=>'User::CheckLevel(10)',
			),
			array('allow', // allow coordinator
				'actions'=>array('delete'),
				'expression'=>'User::CheckLevel(20)',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionQuickSearch($text)
	{
		$result = Ticket::quick_search($text);
		if (is_array($result))
		{
			$data = new CActiveDataProvider('Ticket', array('data' => $result));
			$this->render('index',array(
				'dataProvider'=>$data,
			));
		} else {
			$this->actionView($result->id);
		}
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		$innerListProvider=new CActiveDataProvider('Ticket', array('criteria'=>array(
				// открытые цели, незакрытые задач
				'condition'=>'parent_ticket_id = :tid',
				'params'=>array(':tid'=>$id),
				'order'=>Ticket::$orderString,
			),
			'pagination' => array('pageSize'=>30)));
		$this->render('view',array(
			'model'=>$model,
			'innerListProvider'=>$innerListProvider
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=Ticket::create();

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);
		if(isset($_POST['Ticket']))
		{
			$model->attributes=$_POST['Ticket'];
			if($model->save())
			{
				Sendmail::mailAssignTicket($model);
				$this->redirect(array('view','id'=>$model->id));
			}
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
		//$this->performAjaxValidation($model);

		if(isset($_POST['Ticket']))
		{
			$prevAll = CJSON::encode($model);
			$prevOwner = $model->owner_user_id;
			$model->attributes=$_POST['Ticket'];
			if($model->save())
			{
				if ($model->owner_user_id != $prevOwner) Sendmail::mailAssignTicket($model);
				if (CJSON::encode($model) != $prevAll) Sendmail::mailChangeTicket($model);
				$this->redirect(array('view','id'=>$model->id));
			}
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
		$dataProvider=new CActiveDataProvider('Ticket', array('criteria'=>array(
				// открытые цели, незакрытые задач
				'condition'=>'status_id < 3',
				'order'=>Ticket::$orderString,
			),
			'pagination' => array('pageSize'=>20)));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionUsersTasks($id)
	{
		$dataProvider=new CActiveDataProvider('Ticket', array(
			'criteria'=>array(
				// открытые цели, незакрытые задач
				'condition'=>'status_id < 3 AND owner_user_id = '.$id,
				'order'=>Ticket::$orderString,
			),'pagination'=>array(
				'pageSize'=>20,
			),
		));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionEpicTasks($id)
	{
		$dataProvider=new CActiveDataProvider('Ticket', array(
			'criteria'=>array(
				// открытые цели, незакрытые задач
				'condition'=>'status_id < 3 AND parent_ticket_id = '.$id,
				'order'=>Ticket::$orderString,
			),'pagination'=>array(
				'pageSize'=>20,
			),
		));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	/**
	 * Create or update tasks from ajax.
	 */
	public function actionAjaxEdit()
	{
		//Yii::log("edit input: ".CJSON::encode($_POST['Ticket']), "debug");
		$model=Ticket::create('plan');
		CActiveForm::validate($model);
		if ($model->HasErrors()) echo CJSON::encode($model->getErrors());
		else if(isset($_POST['Ticket']))
		{
			$model->attributes=$_POST['Ticket'];
			if ($model->id) $model->isNewRecord = false;
			if (!$model->save()) Yii::log(CJSON::encode($model->getErrors()), "error");
		}
	}
	
	/**
	 * Specific planning interface.
	 */
	public function actionPlan()
	{
		$filter_new = 0;
		$layout='//layouts/column1';
		
		$model=Ticket::create();
		
		$dataProvider=new CActiveDataProvider('Ticket', array(
			'criteria'=>array(
				// открытые цели, незакрытые задач
				'condition'=>'status_id < 3 AND 
					(ticket_type_id = 1 OR parent_ticket_id IS NOT NULL)'.
					($filter_new ? " AND create_date > '".date("Y-m-d", time() - 2*24*60*60)."'" : ""),
				// сортировка по целям
				'order'=>'if (ticket_type_id = 1, 100 * id, parent_ticket_id * 100 + 1), due_date',
				//'with'=>array('author'),
			),'pagination'=>false,
		));
		$this->render('plan',array(
			'dataProvider'=>$dataProvider,
			'filter_new' => $filter_new,
			'model' => $model
		));
	}
	
	public function actionMakeWF($id, $action)
	{
		$model=$this->loadModel($id);
		if (isset($_POST['Ticket']))
		{
			$resolution = (int)$_POST['Ticket']['resolution_id'];
			$worked_time = (int)$_POST['Ticket']['worked_time'];
			$model->makeWorkflowAction($action, $resolution, $worked_time);
		} else $model->makeWorkflowAction($action);
		if($model->save())
			$this->redirect(array('view','id'=>$model->id));

	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Ticket('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Ticket']))
			$model->attributes=$_GET['Ticket'];

		$this->render('admin',array(
			'model'=>$model,
		));
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

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ticket-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
