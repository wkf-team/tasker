<?php

/**
 * This is the model class for table "iteration".
 *
 * The followings are the available columns in table 'iteration':
 * @property integer $id
 * @property string $start_date
 * @property string $due_date
 * @property integer $project_id
 * @property integer $status_id
 * @property integer $number
 *
 * The followings are the available model relations:
 * @property Project $project
 * @property Status $status
 * @property SubTicket[] $subTickets
 * @property Ticket[] $tickets
 * @property TicketHistory[] $ticketHistories
 */
class Iteration extends CActiveRecord
{	
	public function getLabel() {
		return "Sprint ".$this->number.", due to ".$this->encodeDate($this->due_date);
	}
	
	public function start() {
		for ($i = 0; $i < count($this->tickets); $i++) $this->startTicket($this->tickets[$i]);
		$this->status_id = 4;
		return $this->save();
	}
	
	//if blocked - set to onhold
	//else set to in progress
	private function startTicket($ticket) {
		if ($ticket->status_id == 1) $ticket->status_id = $ticket->is_blocked() ? 3 : 4;
		$ticket->save();
		for ($i = 0; $i < count($ticket->tickets); $i++) $this->startTicket($ticket->tickets[$i]);
	}
	
	public function rollup() {
		$next_id = 0;
		//if not last - find next
		if ($this->project->iterations[count($this->project->iterations)-1]->id != $this->id) {
			for ($i = count($this->project->iterations)-1; $this->project->iterations[$i]->id != $this->id; $i--);
			$next_id = $this->project->iterations[$i+1]->id;
		} else { //or create new
			$new = new Iteration();
			$new->start_date = $this->due_date;
			$new->due_date = date("Y-m-d", strtotime($this->due_date) + strtotime($this->due_date) - strtotime($this->start_date));
			$new->project_id = $this->project_id;
			$new->number = $this->number + 1;
			$new->status_id = 1;
			if (!$new->save()) {
				$this->addError('id', "New iteration is not created: ".CJSON::encode($new->errors));
				return false;
			}
			$next_id = $new->id;
		}
		//all unresolved tickets move to the next
		$hasUnresolved = false;
		$hasResolved = false;
		for ($i = 0; $i < count($this->tickets); $i++) {
			if ($this->tickets[$i]->resolution_id != null) {
				$hasResolved = true;
			}
			if ($this->tickets[$i]->resolution_id == null) {
				$hasUnresolved = true;
				$this->tickets[$i]->iteration_id = $next_id;
				if (!$this->tickets[$i]->save()) {
					$this->addError('tickets', 'ticket_'.$this->tickets[$i]->id.CJSON::encode($this->tickets[$i]->errors));
					return false;
				}
			}
		}
		// close this
		$this->status_id = $hasUnresolved ? ($hasResolved ? 9 : 8) : 10;
		return $this->save();
	}
	
	public function getTotalSP() {
		$r = new CDbCriteria([
			'select'=>'sum(story_points)',
			'condition'=>'iteration_id='.$this->id
		]);
		//echo CJSON::encode($r);
		//die();
		
		return Ticket::model()->find([
			'select'=>'sum(story_points) as story_points',
			'condition'=>'iteration_id='.$this->id
		])->story_points;
	}
	
	public function encodeDate($date)
	{
		if ($date == null || $date == "") return $date;
		$dt = new DateTime($date);
		return CHtml::encode($dt->format("d.m.Y"));
	}
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Iteration the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'iteration';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('start_date, due_date, project_id, status_id, number', 'required'),
			array('project_id, status_id, number', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, start_date, due_date, project_id, status_id, number', 'safe', 'on'=>'search'),
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
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'status' => array(self::BELONGS_TO, 'Status', 'status_id'),
			'subTickets' => array(self::HAS_MANY, 'SubTicket', 'iteration_id'),
			'tickets' => array(self::HAS_MANY, 'Ticket', 'iteration_id'),
			'ticketHistories' => array(self::HAS_MANY, 'TicketHistory', 'iteration_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'start_date' => 'Start Date',
			'due_date' => 'Due Date',
			'project_id' => 'Project',
			'status_id' => 'Status',
			'number' => 'Number',
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
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('due_date',$this->due_date,true);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('number',$this->number);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}