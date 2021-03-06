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
 * @property double $estimate_time
 * @property double $worked_time
 * @property integer $story_points
 * @property integer $priority_id
 * @property integer $status_id
 * @property integer $resolution_id
 * @property integer $ticket_type_id
 * @property integer $author_user_id
 * @property integer $owner_user_id
 * @property integer $tester_user_id
 * @property integer $responsible_user_id
 * @property integer $parent_ticket_id
 * @property integer $iteration_id
 * @property integer $project_id
 * @property string $initial_version
 * @property string $resolved_version
 *
 * The followings are the available model relations:
 * @property Attachment[] $attachments
 * @property Comment[] $comments
 * @property Relation[] $reTicketsFrom
 * @property Relation[] $relTicketsTo
 * @property SpentTime[] $spentTimes
 * @property SubTicket[] $subTickets
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
 * @property Iteration $iteration
 * @property Project $project
 */
class Ticket extends CActiveRecord
{
	//public static $orderString = "status_id DESC, order_num";
	public $asearch;
	
	public static $orderString = "status_id DESC, if(due_date < CURDATE(),
							1000 + 10*(CURDATE() - due_date) + priority_id,
							if(due_date = CURDATE(), 100 + priority_id,
							if(due_date is null, 90 + priority_id,
							if (due_date - CURDATE() = 1, 80 + priority_id,
							10*priority_id
						)))) DESC, due_date";
						
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Ticket the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function is_blocked() {
		for ($i = 0; $i < count($this->relTicketsTo); $i++)
			if ($this->relTicketsTo[$i]->relation_type_id == 1) return true;
		return false;
	}
	
	protected function beforeSave()
	{
		if ($this->isNewRecord) $this->project_id = Project::GetSelected()->id;
		if ($this->due_date == '') $this->due_date = null;
		if ($this->estimate_start_date == '') $this->estimate_start_date = null;
		return parent::beforeSave();
	}
	
	public static function create($scenario = 'insert')
	{
		$ticket = new Ticket($scenario);
		$ticket->status_id = 1;
		$ticket->ticket_type_id = 2;
		$ticket->priority_id = 2;
		$ticket->author_user_id = Yii::app()->user->id;
		$ticket->owner_user_id = Yii::app()->user->id;
		$ticket->responsible_user_id = Yii::app()->user->id;
		$ticket->project_id = Project::GetSelected()->id;
		$ticket->order_num = Ticket::model()->find(['select'=>'IFNULL(max(order_num),0) as order_num'])->order_num;
		return $ticket;
	}
	
	public function postpone()
	{
		$postpone_time = 3 * 24 * 3600;
		switch ($this->status_id)
		{
			case 1:
			case 2:
				$this->estimate_start_date = date("Y-m-d", time() + $postpone_time);
				if ($this->ownerUser->work_time_per_week > 0) $this->due_date = date("Y-m-d", time() + $postpone_time + $this->estimate_time * 7 * 24 * 3600 / $this->ownerUser->work_time_per_week);
				else $this->due_date = date("Y-m-d", time() + $postpone_time);
				break;
			case 3:
			case 4:
			case 5:
				if ($this->ownerUser->work_time_per_week > 0) $this->due_date = date("Y-m-d", time() + $postpone_time + $this->estimate_time * 7 * 24 * 3600 / $this->ownerUser->work_time_per_week);
				else $this->due_date = date("Y-m-d", time() + $postpone_time);
				break;
			default: break;
		}
		$this->save();
	}
	
	public function calculateEstimateStartDate()
	{
		if ($this->ownerUser->work_time_per_week > 0 && $this->due_date > '' && $this->status_id == 1) {
			$this->estimate_start_date = date("Y-m-d", strtotime($this->due_date) - $this->estimate_time * 7 * 24 * 3600 / $this->ownerUser->work_time_per_week);
		} else {
			$this->estimate_start_date = $this->due_date;
		}
	}
	
	public static function quick_search($text)
	{
		if (is_numeric ($text)) {
			$id = (int)$text;
			$model = Ticket::model()->findByPk($id);
			if ($model && UserHasProject::HasUserAccess($model->project_id, Yii::app()->user->id))
				return $model;
		}
		$result = Ticket::model()->findAll(array(
			'condition'=>'(subject LIKE :qstr OR description LIKE :qstr) AND p.user_id = :uid',
			'join'=>'INNER JOIN user_has_project AS p ON p.project_id = t.project_id',
			'params'=>array(':qstr'=>"%".$text."%", ':uid'=>Yii::app()->user->id)
		));
		if (count($result) == 1) return $result[0];
		else return $result;
	}
	
	public function encodeDate($date)
	{
		if ($date == null || $date == "") return $date;
		$dt = new DateTime($date);
		return CHtml::encode($dt->format("d.m.Y"));
	}
	
	public function getWorkflowActions()
	{
		return WorkflowStep::GetListOfActions($this);
	}
	
	public function makeWorkflowAction($action)
	{
		WorkflowStep::GetAction($this->status_id, $action)->PerformStep($this);
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
			array('subject, priority_id, status_id, ticket_type_id, owner_user_id, responsible_user_id', 'required'),
			array('priority_id, status_id, story_points, resolution_id, ticket_type_id, author_user_id, owner_user_id, tester_user_id, responsible_user_id, parent_ticket_id, iteration_id, project_id', 'numerical', 'integerOnly'=>true),
			array('estimate_time, worked_time', 'numerical'),
			array('subject', 'length', 'max'=>255),
			array('description', 'length', 'max'=>10000),
			array('initial_version, resolved_version', 'length', 'max'=>25),
			array('estimate_start_date, due_date, end_date', 'safe'),
			array('id', 'safe', 'on' => 'plan'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, subject, description, create_date, estimate_start_date, due_date, end_date, estimate_time, worked_time, priority_id, status_id, resolution_id, ticket_type_id, author_user_id, owner_user_id, tester_user_id, responsible_user_id, parent_ticket_id, iteration_id, project_id, initial_version, resolved_version, asearch', 'safe', 'on'=>'search'),
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
			'attachments' => array(self::HAS_MANY, 'Attachment', 'ticket_id'),
			'comments' => array(self::HAS_MANY, 'Comment', 'ticket_id'),
			'relTicketsFrom' => array(self::HAS_MANY, 'Relation', 'ticket_from_id'),
			'relTicketsTo' => array(self::HAS_MANY, 'Relation', 'ticket_to_id'),
			'spentTimes' => array(self::HAS_MANY, 'SpentTime', 'ticket_id'),
			'subTickets' => array(self::HAS_MANY, 'SubTicket', 'ticket_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'iteration' => array(self::BELONGS_TO, 'Iteration', 'iteration_id'),
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
			'estimate_start_date' => 'План начала',
			'due_date' => 'Срок',
			'end_date' => 'Дата закрытия',
			'estimate_time' => 'Оценка времени (ч)',
			'worked_time' => 'Затраченное время (ч)',
			'story_points' => 'Story points',
			'priority_id' => 'Приоритет',
			'status_id' => 'Статус',
			'resolution_id' => 'Резолюция',
			'ticket_type_id' => 'Тип',
			'author_user_id' => 'Автор',
			'owner_user_id' => 'Текущий владелец',
			'tester_user_id' => 'QA',
			'responsible_user_id' => 'Ответственный',
			'parent_ticket_id' => 'Родительская задача',
			'iteration_id' => 'Итерация',
			'project_id' => 'Проект',
			'initial_version' => 'Обнаружено в версии',
			'resolved_version' => 'Исправлено в версии',
			'asearch' => 'Условие WHERE',
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
		$noticeText = "";
		foreach($this->relTicketsTo as $relation) {
			if ($relation->relation_type_id == 1) {
				if ($withHtml) $relText .= CHtml::link(CHtml::encode($relation->ticket_from_id), array('ticket/view', 'id'=>$relation->ticket_from_id)).", ";
				else $relText .= $relation->ticket_from_id.", ";
				$noticeText .= $relation->ticketFrom->subject."\n";
			}
		}
		if ($relText != "")
		{
			$relText = substr($relText, 0, strlen($relText) - 2);
			if ($withHtml) {
				$relText .= " ".CHtml::tag("span", [
					'class'=>'ui-icon ui-icon-info',
					'title'=>$noticeText,
					'style'=>'display:inline-block',
				]);
			}
		}
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
		$criteria->compare('story_points',$this->story_points);
		$criteria->compare('priority_id',$this->priority_id);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('resolution_id',$this->resolution_id);
		$criteria->compare('ticket_type_id',$this->ticket_type_id);
		$criteria->compare('author_user_id',$this->author_user_id);
		$criteria->compare('owner_user_id',$this->owner_user_id);
		$criteria->compare('tester_user_id',$this->tester_user_id);
		$criteria->compare('responsible_user_id',$this->responsible_user_id);
		$criteria->compare('parent_ticket_id',$this->parent_ticket_id);
		$criteria->compare('iteration_id',$this->iteration_id);
		$criteria->compare('t.project_id',$this->project_id);
		$criteria->compare('initial_version',$this->initial_version,true);
		$criteria->compare('resolved_version',$this->resolved_version,true);
		$criteria->addCondition('p.user_id = '.Yii::app()->user->id);
		if ($this->asearch > '') {
			$this->asearch = str_replace(';', '\;', $this->asearch);
			$criteria->addCondition($this->asearch);
		}
		$criteria->join = 'INNER JOIN user_has_project AS p ON p.project_id = t.project_id';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}