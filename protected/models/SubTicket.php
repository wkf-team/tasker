<?php

/**
 * This is the model class for table "sub_ticket".
 *
 * The followings are the available columns in table 'sub_ticket':
 * @property integer $id
 * @property string $text
 * @property integer $order_num
 * @property integer $is_done
 * @property integer $iteration_id
 * @property integer $ticket_id
 *
 * The followings are the available model relations:
 * @property Iteration $iteration
 * @property Ticket $ticket
 */
class SubTicket extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sub_ticket';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('text, order_num, is_done, ticket_id', 'required'),
			array('order_num, is_done, iteration_id, ticket_id', 'numerical', 'integerOnly'=>true),
			array('text', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, text, order_num, is_done, iteration_id, ticket_id', 'safe', 'on'=>'search'),
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
			'iteration' => array(self::BELONGS_TO, 'Iteration', 'iteration_id'),
			'ticket' => array(self::BELONGS_TO, 'Ticket', 'ticket_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'text' => 'Text',
			'order_num' => 'Order Num',
			'is_done' => 'Is Done',
			'iteration_id' => 'Iteration',
			'ticket_id' => 'Ticket',
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
		$criteria->compare('text',$this->text,true);
		$criteria->compare('order_num',$this->order_num);
		$criteria->compare('is_done',$this->is_done);
		$criteria->compare('iteration_id',$this->iteration_id);
		$criteria->compare('ticket_id',$this->ticket_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SubTicket the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
