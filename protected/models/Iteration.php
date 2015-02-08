<?php

/**
 * This is the model class for table "iteration".
 *
 * The followings are the available columns in table 'iteration':
 * @property integer $id
 * @property string $due_date
 * @property integer $project_id
 *
 * The followings are the available model relations:
 * @property Project $project
 * @property SubTicket[] $subTickets
 * @property Ticket[] $tickets
 * @property TicketHistory[] $ticketHistories
 */
class Iteration extends CActiveRecord
{
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
			array('due_date, project_id', 'required'),
			array('project_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, due_date, project_id', 'safe', 'on'=>'search'),
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
			'due_date' => 'Due Date',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('due_date',$this->due_date,true);
		$criteria->compare('project_id',$this->project_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Iteration the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
