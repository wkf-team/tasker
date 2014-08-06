<?php

/**
 * This is the model class for table "ticket".
 *
 * The followings are the available columns in table 'ticket':
 * @property integer $id
 * @property string $subject
 * @property string $description
 * @property string $create_date
 * @property string $estimate_start_date
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
 * @property integer $tester_user_id
 * @property integer $responsible_user_id
 * @property integer $parent_ticket_id
 *
 * The followings are the available model relations:
 * @property Attachement[] $attachements
 * @property Comment[] $comments
 * @property Relation[] $relations
 * @property Relation[] $relations1
 * @property Priority $priority
 * @property Status $status
 * @property Resolution $resolution
 * @property TicketType $ticketType
 * @property User $authorUser
 * @property User $ownerUser
 * @property Ticket $parentTicket
 * @property Ticket[] $tickets
 * @property User $testerUser
 * @property User $responsibleUser
 */
class Ticket extends CActiveRecord
{
	public static $orderString = "status_id DESC, if(due_date is null, CURDATE() + 365, due_date), priority_id DESC";
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
		if ($this->estimate_start_date == '') $this->estimate_start_date = null;
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
		$user = $user ? $user->id : $ticket->author_user_id;
		$ticket->owner_user_id = $user;
		$ticket->responsible_user_id = $user;
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
			array('subject, priority_id, status_id, resolution_id, ticket_type_id, owner_user_id, responsible_user_id', 'required'),
			array('estimate_time, worked_time, priority_id, status_id, resolution_id, ticket_type_id, author_user_id, owner_user_id, tester_user_id, responsible_user_id, parent_ticket_id', 'numerical', 'integerOnly'=>true),
			array('subject', 'length', 'max'=>255),
			array('description', 'length', 'max'=>10000),
			array('estimate_start_date, due_date, end_date', 'safe'),
			array('id', 'safe', 'on' => 'plan'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, subject, description, create_date, estimate_start_date, due_date, end_date, estimate_time, worked_time, priority_id, status_id, resolution_id, ticket_type_id, author_user_id, owner_user_id, tester_user_id, responsible_user_id, parent_ticket_id', 'safe', 'on'=>'search'),
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
			'attachements' => array(self::HAS_MANY, 'Attachement', 'ticket_id'),
			'comments' => array(self::HAS_MANY, 'Comment', 'ticket_id'),
			'relTicketsFrom' => array(self::HAS_MANY, 'Relation', 'ticket_from_id'),
			'relTicketsTo' => array(self::HAS_MANY, 'Relation', 'ticket_to_id'),
			'priority' => array(self::BELONGS_TO, 'Priority', 'priority_id'),
			'status' => array(self::BELONGS_TO, 'Status', 'status_id'),
			'resolution' => array(self::BELONGS_TO, 'Resolution', 'resolution_id'),
			'ticketType' => array(self::BELONGS_TO, 'TicketType', 'ticket_type_id'),
			'authorUser' => array(self::BELONGS_TO, 'User', 'author_user_id'),
			'ownerUser' => array(self::BELONGS_TO, 'User', 'owner_user_id'),
			'parentTicket' => array(self::BELONGS_TO, 'Ticket', 'parent_ticket_id'),
			'tickets' => array(self::HAS_MANY, 'Ticket', 'parent_ticket_id'),
			'testerUser' => array(self::BELONGS_TO, 'User', 'tester_user_id'),
			'responsibleUser' => array(self::BELONGS_TO, 'User', 'responsible_user_id'),
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
			'estimate_start_date' => 'План старт',
			'due_date' => 'Срок',
			'end_date' => 'Дата закрытия',
			'estimate_time' => 'Оценка времени (ч)',
			'worked_time' => 'Затраченное время (ч)',
			'priority_id' => 'Приоритет',
			'status_id' => 'Статус',
			'resolution_id' => 'Резолюция',
			'ticket_type_id' => 'Тип',
			'author_user_id' => 'Автор',
			'owner_user_id' => 'Текущий владелец',
			'tester_user_id' => 'QA',
			'responsible_user_id' => 'Ответственный',
			'parent_ticket_id' => 'Родительская задача',
		);
	}
	
	public function __get($name) {
		if ($name == "blocked_by") return $this->GetBlockedBy_ValueString();
		return parent::__get($name);
	}
	
	public $includeBlockedBy = false;
	public function getIterator()
	{
		$attributes=$this->getAttributes();
		//without this hack I am receiving PHP Warning while updating ticket
		//TODO check and fix it
		if ($this->includeBlockedBy) $attributes['blocked_by'] = $this->blocked_by;
		return new CMapIterator($attributes);
	}
	
	public function GetBlockedBy_HtmlString()
	{
		return $this->GetBlockedBy(true);
	}
	
	public function GetBlockedBy_ValueString()
	{
		return $this->GetBlockedBy(false);
	}
	
	private function GetBlockedBy($withHtml)
	{
		$relText = "";
		foreach($this->relTicketsTo as $relation) {
			if ($relation->relation_type_id == 1) {
				if ($withHtml) $relText .= CHtml::link(CHtml::encode($relation->ticket_from_id), array('view', 'id'=>$relation->ticket_from_id)).", ";
				else $relText .= $relation->ticket_from_id.", ";
			}
		}
		if ($relText != "") $relText = substr($relText, 0, strlen($relText) - 2);
		return $relText;
	}
	
	public function UpdateBlockedBy($value)
	{
		// check blocked_by field
		if ($value == "") // make no relation
		{
			foreach($this->relTicketsTo as $relation) {
				if ($relation->relation_type_id == 1) {
					$relation->delete();
				}
			}
		} else { // parse relations
			$ids = explode(",", $value);
			$ids_array = array();
			//add if new
			foreach($ids as $id_s) {
				$id = (int)trim($id_s);
				// id is digits, id is not current and ticket with id exists
				if ($id > 0 && $id != $this->id && Ticket::model()->findByPk($id) != null) {
					//if not found then add relation
					if(Relation::model()->findByAttributes(array('ticket_to_id' => $this->id, 'ticket_from_id' => $id, 'relation_type_id' => 1)) == null) {
						$rel = new Relation();
						$rel->ticket_to_id = $this->id;
						$rel->ticket_from_id = $id;
						$rel->relation_type_id = 1;
						if (!$rel->save()) Yii::log(CJSON::encode($rel->getErrors()), "error");
					}
					$ids_array[] = $id;
				} else Yii::log("UpdateBlockedBy for ticket ".$this->id." has wrong input: ".$id_s, "warning");
			}
			//delete if not exist
			foreach($this->relTicketsTo as $relation) {
				if ($relation->relation_type_id == 1 && !in_array($relation->ticket_from_id, $ids_array)) {
					$relation->delete();
				}
			}
		}
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
		$criteria->compare('estimate_start_date',$this->estimate_start_date,true);
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
		$criteria->compare('tester_user_id',$this->tester_user_id);
		$criteria->compare('responsible_user_id',$this->responsible_user_id);
		$criteria->compare('parent_ticket_id',$this->parent_ticket_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}