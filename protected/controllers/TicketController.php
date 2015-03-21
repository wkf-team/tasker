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
			array('allow',  // allow authenticated users
				'actions'=>array('index','usersTasks','epicTasks','QuickSearch','admin'),
				'users'=>array('@'),
			),
			array('allow',  // allow by project control
				'actions'=>array('view','update'),
				'expression'=>'User::CheckLevel(10) && UserHasProject::HasUserAccess($model->project_id, Yii::app()->user->id)',
			),
			array('allow', // allow for participants
				'actions'=>array('create','admin','plan','AjaxEdit','makeWF','postpone'),
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
	
	public function actionPostpone($id)
	{
		$model = $this->loadModel($id);
		$model->postpone();
		Sendmail::mailChangeTicketDate($model);
		$this->redirect(array('ticket/view', 'id'=>$model->id));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		$model->project->SetSelected();
		$this->render('view',array(
			'model'=>$model
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
		//$this->performAjaxValidation($model);
		if(isset($_POST['Ticket']))
		{
			$model->attributes=$_POST['Ticket'];
			if ($_POST['estimate_start_auto']) $model->calculateEstimateStartDate();
			if ($_POST['responsible_auto']) $model->responsible_user_id = $model->owner_user_id;
			if($model->save())
			{
				if (isset($_POST['sendNotifications'])) Sendmail::mailAssignTicket($model);
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
			$prevEst = $model->estimate_start_date;
			$prevDue = $model->due_date;
			$prevOwner = $model->owner_user_id;
			$model->attributes=$_POST['Ticket'];
			if ($model->due_date != $prevDue && $_POST['estimate_start_auto']) $model->calculateEstimateStartDate();
			if ($model->owner_user_id != $prevOwner && $_POST['responsible_auto']) $model->responsible_user_id = $model->owner_user_id;
			if($model->save())
			{
				if (isset($_POST['sendNotifications'])) 
				{
					if ($model->owner_user_id != $prevOwner) Sendmail::mailAssignTicket($model);
					elseif ($model->estimate_start_date != $prevEst || $model->due_date != $prevDue) Sendmail::mailChangeTicketDate($model);
					elseif (CJSON::encode($model) != $prevAll) Sendmail::mailChangeTicket($model);
				}
				if (isset($_POST['Comment']) && $_POST['Comment'] > '')
				{
					$comment = new Comment();
					$comment->attributes=$_POST['Comment'];
					$comment->SetDefault($id);
					$comment->save();
				}
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
		$model = $this->loadModel($id);
		if (count($model->attachments) > 0)
			foreach ($model->attachments as $att) $att->delete();
		$model->delete();

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
				'condition'=>'status_id < 6 AND p.user_id = :uid',
				'join'=>'INNER JOIN user_has_project AS p ON p.project_id = t.project_id',
				'params'=>array(':uid'=>Yii::app()->user->id),
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
				'condition'=>'status_id < 6 AND p.user_id = :uid AND owner_user_id = :oid',
				'join'=>'INNER JOIN user_has_project AS p ON p.project_id = t.project_id',
				'params'=>array(':uid'=>Yii::app()->user->id, ':oid'=>$id),
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
				'condition'=>'status_id < 6 AND p.user_id = :uid AND parent_ticket_id = :pid',
				'join'=>'INNER JOIN user_has_project AS p ON p.project_id = t.project_id',
				'params'=>array(':uid'=>Yii::app()->user->id, ':pid'=>$id),
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
		if (isset($_POST['Ticket']['id']) && $_POST['Ticket']['id']) {
			$model=$this->loadModel($_POST['Ticket']['id']);
		} else {
			$model=Ticket::create();
			unset($_POST['Ticket']['id']);
		}
		if(isset($_POST['Ticket']))
		{
			$prevDue = $model->due_date;
			$prevOwner = $model->owner_user_id;
			$model->attributes=$_POST['Ticket'];
			if ($model->due_date != $prevDue) $model->calculateEstimateStartDate();
			if ($model->owner_user_id != $prevOwner) $model->responsible_user_id = $model->owner_user_id;
			if ($model->id) $model->isNewRecord = false;
			if (!$model->save()) Yii::log(CJSON::encode($model->getErrors()), "error");
			else $model->UpdateBlockedBy($_POST['blocked_by']);
		}
	}
	
	/**
	 * Specific planning interface.
	 */
	public function actionPlan()
	{
		$filter_new = 0;
		//$this->layout='//layouts/column1';
		
		$model=Ticket::create();
		
		$dataProvider=new CActiveDataProvider('Ticket', array(
			'criteria'=>array(
				// открытые цели, незакрытые задач
				'condition'=>'status_id < 6 AND p.user_id = :uid'.
					//(ticket_type_id = 1 OR parent_ticket_id IS NOT NULL)'.
					($filter_new ? " AND create_date > :cd'".date("Y-m-d", time() - 2*24*60*60)."'" : ""),
				'join'=>'INNER JOIN user_has_project AS p ON p.project_id = t.project_id',
				'params'=>array(':uid'=>Yii::app()->user->id),
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
			$model->resolution_id = (int)$_POST['Ticket']['resolution_id'];
			$model->worked_time = (int)$_POST['Ticket']['worked_time'];
		}
		$user_id = Yii::app()->user->id;
		if (User::CheckLevel(20) ||
			$model->owner_user_id == $user_id ||
			$model->responsible_user_id == $user_id ||
			$model->author_user_id == $user_id)
		{
			$model->makeWorkflowAction($action);
			if (isset($_POST['Comment']) && $_POST['Comment'] > '')
			{
				$comment = new Comment();
				$comment->attributes=$_POST['Comment'];
				$comment->SetDefault($id);
				$comment->save();
			}
			Sendmail::mailChangeTicket($model);
		}
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
