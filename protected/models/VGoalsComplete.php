<?php

/**
 * This is the model class for table "v_goals_complete".
 *
 * The followings are the available columns in table 'v_goals_complete':
 * @property integer $id
 * @property string $subject
 * @property string $total
 * @property string $closed
 */
class VGoalsComplete extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return VGoalsComplete the static model class
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
		return 'v_goals_complete';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subject', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('subject', 'length', 'max'=>255),
			array('total', 'length', 'max'=>21),
			array('closed', 'length', 'max'=>23),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, subject, total, closed', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'subject' => 'Subject',
			'total' => 'Total',
			'closed' => 'Closed',
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
		$criteria->compare('total',$this->total,true);
		$criteria->compare('closed',$this->closed,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}