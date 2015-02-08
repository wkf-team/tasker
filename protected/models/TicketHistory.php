<?php

/**
 * This is the model class for table "ticket_history".
 *
 * The followings are the available columns in table 'ticket_history':
 * @property integer $hist_id
 * @property string $hist_create_date
 * @property integer $hist_create_user_id
 * @property string $hist_reason
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
 * @property integer $iteration_id
 * @property integer $project_id
 *
 * The followings are the available model relations:
 * @property Priority $priority
 * @property Status $status
 * @property Resolution $resolution
 * @property TicketType $ticketType
 * @property User $authorUser
 * @property User $ownerUser
 * @property User $testerUser
 * @property User $responsibleUser
 * @property Iteration $iteration
 * @property Project $project
 * @property User $histCreateUser
 */
class TicketHistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ticket_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hist_create_date, hist_create_user_id, hist_reason, id, subject, create_date, priority_id, status_id, resolution_id, ticket_type_id, author_user_id, owner_user_id, responsible_user_id, iteration_id, project_id', 'required'),
			array('hist_create_user_id, id, estimate_time, worked_time, priority_id, status_id, resolution_id, ticket_type_id, author_user_id, owner_user_id, tester_user_id, responsible_user_id, parent_ticket_id, iteration_id, project_id', 'numerical', 'integerOnly'=>true),
			array('hist_reason', 'length', 'max'=>1024),
			array('subject', 'length', 'max'=>255),
			array('description', 'length', 'max'=>10000),
			array('estimate_start_date, due_date, end_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('hist_id, hist_create_date, hist_create_user_id, hist_reason, id, subject, description, create_date, estimate_start_date, due_date, end_date, estimate_time, worked_time, priority_id, status_id, resolution_id, ticket_type_id, author_user_id, owner_user_id, tester_user_id, responsible_user_id, parent_ticket_id, iteration_id, project_id', 'safe', 'on'=>'search'),
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
			'testerUser' => array(self::BELONGS_TO, 'User', 'tester_user_id'),
			'responsibleUser' => array(self::BELONGS_TO, 'User', 'responsible_user_id'),
			'iteration' => array(self::BELONGS_TO, 'Iteration', 'iteration_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'histCreateUser' => array(self::BELONGS_TO, 'User', 'hist_create_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'hist_id' => 'Hist',
			'hist_create_date' => 'Hist Create Date',
			'hist_create_user_id' => 'Hist Create User',
			'hist_reason' => 'Hist Reason',
			'id' => 'ID',
			'subject' => 'Subject',
			'description' => 'Description',
			'create_date' => 'Create Date',
			'estimate_start_date' => 'Estimate Start Date',
			'due_date' => 'Due Date',
			'end_date' => 'End Date',
			'estimate_time' => 'Estimate Time',
			'worked_time' => 'Worked Time',
			'priority_id' => 'Priority',
			'status_id' => 'Status',
			'resolution_id' => 'Resolution',
			'ticket_type_id' => 'Ticket Type',
			'author_user_id' => 'Author User',
			'owner_user_id' => 'Owner User',
			'tester_user_id' => 'Tester User',
			'responsible_user_id' => 'Responsible User',
			'parent_ticket_id' => 'Parent Ticket',
			'iteration_id' => 'Iteration',
			'project_id' => 'Project',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('hist_id',$this->hist_id);
		$criteria->compare('hist_create_date',$this->hist_create_date,true);
		$criteria->compare('hist_create_user_id',$this->hist_create_user_id);
		$criteria->compare('hist_reason',$this->hist_reason,true);
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
		$criteria->compare('iteration_id',$this->iteration_id);
		$criteria->compare('project_id',$this->project_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TicketHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
