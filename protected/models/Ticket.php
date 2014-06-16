<?php

/**
 * This is the model class for table "ticket".
 *
 * The followings are the available columns in table 'ticket':
 * @property integer $id
 * @property string $subject
 * @property string $description
 * @property string $create_date
 * @property string $due_date
 * @property string $end_date
 * @property integer $estimate_time
 * @property integer $worked_time
 * @property integer $priority_id
 * @property integer $status_id
 * @property integer $resolution_id
 * @property integer $ticket_type_id
 * @property integer $author_user_id
 * @property integer $owner_user_id
 * @property integer $parent_ticket_id
 *
 * The followings are the available model relations:
 * @property Priority $priority
 * @property Status $status
 * @property Resolution $resolution
 * @property TicketType $ticketType
 * @property User $authorUser
 * @property User $ownerUser
 * @property Ticket $parentTicket
 * @property Ticket[] $tickets
 */
class Ticket extends CActiveRecord
{
	public static $orderString = "status_id DESC, due_date, priority_id DESC";
	/*
	public static $orderString = "if(due_date < CURDATE(),
							1000 + 10*(CURDATE() - due_date) + priority_id,
							if(due_date = CURDATE(), 100 + priority_id,
							if(due_date is null, 90 + priority_id,
							if (due_date - CURDATE() = 1, 80 + priority_id,
							10*priority_id
						)))) DESC, due_date";
						*/
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Ticket the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function beforeSave()
	{
		if ($this->due_date == '') $this->due_date = null;
		return parent::beforeSave();
	}
	
	public static function create($scenario = 'insert')
	{
		$ticket = new Ticket($scenario);
		$ticket->status_id = 1;
		$ticket->ticket_type_id = 2;
		$ticket->resolution_id = 1;
		$ticket->priority_id = 2;
		$ticket->author_user_id = Yii::app()->user->id;
		// default is coordinator
		$user = User::model()->findByAttributes(array('usergroup_id'=>3));
		$ticket->owner_user_id = $user ? $user->id : $ticket->author_user_id;
		return $ticket;
	}
	
	public static function quick_search($text)
	{
		if (is_numeric ($text)) {
			$id = (int)$text;
			$model = Ticket::model()->findByPk($id);
			if ($model) return $model;
		}
		$cond = new CDbCriteria();
		$cond->addSearchCondition('subject', $text);
		$cond->addSearchCondition('description', $text, true, 'OR');
		$result = Ticket::model()->findAll($cond);
		if (count($result) == 1) return $result[0];
		else return $result;
	}
	
	public function getWorkflowActions()
	{
		switch($this->status_id) {
			case 1: return array(array('name'=>'start', 'needResolution'=>false), array('name'=>'close', 'needResolution'=>true));
			case 2: return array(array('name'=>'done', 'needResolution'=>true), array('name'=>'close', 'needResolution'=>true));
			case 3: return array(array('name'=>'reopen', 'needResolution'=>false), array('name'=>'close', 'needResolution'=>false));
			case 4: return array(array('name'=>'reopen', 'needResolution'=>false));
		}
	}
	
	public function encodeDate($date)
	{
		if ($date == null || $date == "") return $date;
		$dt = new DateTime($date);
		return CHtml::encode($dt->format("d.m.Y"));
	}
	
	public function makeWorkflowAction($action, $resolution = null, $worked_time = null)
	{
		switch ($action) {
			case 'start' :
			$this->owner_user_id = Yii::app()->user->id;
			$this->status_id = 2;
			break;
			case 'done' : 
			$this->status_id = 3;
			$this->resolution_id = $resolution;
			$this->worked_time = $worked_time;
			$this->end_date = date("Y-m-d");
			break;
			case 'reopen' : 
			$this->status_id = 1;
			$this->end_date = null;
			$this->resolution_id = 1;
			break;
			case 'close' : 
			$this->status_id = 4;
			if(!$this->end_date) $this->end_date = date("Y-m-d");
			if ($resolution) $this->resolution_id = $resolution;
			$this->worked_time = $worked_time;
			break;
		}
	}
	
	public function behaviors(){
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'create_date',
				'updateAttribute' => null,
			)
		);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ticket';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subject, priority_id, status_id, resolution_id, ticket_type_id, owner_user_id', 'required'),
			array('estimate_time, worked_time, priority_id, status_id, resolution_id, ticket_type_id, owner_user_id, parent_ticket_id', 'numerical', 'integerOnly'=>true),
			array('subject', 'length', 'max'=>255),
			array('description', 'length', 'max'=>10000),
			array('due_date, end_date', 'safe'),
			array('id', 'safe', 'on' => 'plan'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, subject, description, create_date, due_date, end_date, estimate_time, worked_time, priority_id, status_id, resolution_id, ticket_type_id, author_user_id, owner_user_id, parent_ticket_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'priority' => array(self::BELONGS_TO, 'Priority', 'priority_id'),
			'status' => array(self::BELONGS_TO, 'Status', 'status_id'),
			'resolution' => array(self::BELONGS_TO, 'Resolution', 'resolution_id'),
			'ticketType' => array(self::BELONGS_TO, 'TicketType', 'ticket_type_id'),
			'authorUser' => array(self::BELONGS_TO, 'User', 'author_user_id'),
			'ownerUser' => array(self::BELONGS_TO, 'User', 'owner_user_id'),
			'parentTicket' => array(self::BELONGS_TO, 'Ticket', 'parent_ticket_id'),
			'tickets' => array(self::HAS_MANY, 'Ticket', 'parent_ticket_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'subject' => 'Тема',
			'description' => 'Описание',
			'create_date' => 'Дата создания',
			'due_date' => 'Срок',
			'end_date' => 'Дата закрытия',
			'estimate_time' => 'Оценка времени (ч)',
			'worked_time' => 'Затраченное время (ч)',
			'priority_id' => 'Приоритет',
			'status_id' => 'Статус',
			'resolution_id' => 'Резолюция',
			'ticket_type_id' => 'Тип',
			'author_user_id' => 'Автор',
			'owner_user_id' => 'Владелец',
			'parent_ticket_id' => 'Родительская задача',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('due_date',$this->due_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('estimate_time',$this->estimate_time);
		$criteria->compare('worked_time',$this->worked_time);
		$criteria->compare('priority_id',$this->priority_id);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('resolution_id',$this->resolution_id);
		$criteria->compare('ticket_type_id',$this->ticket_type_id);
		$criteria->compare('author_user_id',$this->author_user_id);
		$criteria->compare('owner_user_id',$this->owner_user_id);
		$criteria->compare('parent_ticket_id',$this->parent_ticket_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}