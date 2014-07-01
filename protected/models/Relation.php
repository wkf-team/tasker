<?php

/**
 * This is the model class for table "relation".
 *
 * The followings are the available columns in table 'relation':
 * @property integer $id
 * @property integer $ticket_from_id
 * @property integer $ticket_to_id1
 * @property integer $relation_type_id
 *
 * The followings are the available model relations:
 * @property Ticket $ticketFrom
 * @property Ticket $ticketToId1
 * @property RelationType $relationType
 */
class Relation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ticket_from_id, ticket_to_id1, relation_type_id', 'required'),
			array('ticket_from_id, ticket_to_id1, relation_type_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ticket_from_id, ticket_to_id1, relation_type_id', 'safe', 'on'=>'search'),
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
			'ticketFrom' => array(self::BELONGS_TO, 'Ticket', 'ticket_from_id'),
			'ticketToId1' => array(self::BELONGS_TO, 'Ticket', 'ticket_to_id1'),
			'relationType' => array(self::BELONGS_TO, 'RelationType', 'relation_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ticket_from_id' => 'Ticket From',
			'ticket_to_id1' => 'Ticket To Id1',
			'relation_type_id' => 'Relation Type',
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
		$criteria->compare('ticket_from_id',$this->ticket_from_id);
		$criteria->compare('ticket_to_id1',$this->ticket_to_id1);
		$criteria->compare('relation_type_id',$this->relation_type_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Relation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
