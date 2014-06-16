<?php

/**
 * This is the model class for table "v_users_balance".
 *
 * The followings are the available columns in table 'v_users_balance':
 * @property integer $owner_user_id
 * @property string $user_name
 * @property string $total
 * @property string $sum_time
 */
class VUsersBalance extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return VUsersBalance the static model class
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
		return 'v_users_balance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner_user_id', 'required'),
			array('owner_user_id', 'numerical', 'integerOnly'=>true),
			array('user_name', 'length', 'max'=>45),
			array('total', 'length', 'max'=>21),
			array('sum_time', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('owner_user_id, user_name, total, sum_time', 'safe', 'on'=>'search'),
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
			'owner_user_id' => 'Owner User',
			'user_name' => 'User Name',
			'total' => 'Total',
			'sum_time' => 'Sum Time',
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

		$criteria->compare('owner_user_id',$this->owner_user_id);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('total',$this->total,true);
		$criteria->compare('sum_time',$this->sum_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}