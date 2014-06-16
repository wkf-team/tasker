<?php

/**
 * This is the model class for table "v_terms_break".
 *
 * The followings are the available columns in table 'v_terms_break':
 * @property string $error_type
 * @property integer $ticket_id
 * @property string $subject
 * @property string $due_date
 * @property double $calc_date
 * @property integer $user_id
 * @property string $user_name
 */
class VTermsBreak extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return VTermsBreak the static model class
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
		return 'v_terms_break';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ticket_id, user_id', 'numerical', 'integerOnly'=>true),
			array('calc_date', 'numerical'),
			array('error_type', 'length', 'max'=>20),
			array('subject', 'length', 'max'=>255),
			array('user_name', 'length', 'max'=>45),
			array('due_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('error_type, ticket_id, subject, due_date, calc_date, user_id, user_name', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'error_type' => 'Error Type',
			'ticket_id' => 'Ticket',
			'subject' => 'Subject',
			'due_date' => 'Due Date',
			'calc_date' => 'Calc Date',
			'user_id' => 'User',
			'user_name' => 'User Name',
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

		$criteria->compare('error_type',$this->error_type,true);
		$criteria->compare('ticket_id',$this->ticket_id);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('due_date',$this->due_date,true);
		$criteria->compare('calc_date',$this->calc_date);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('user_name',$this->user_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}