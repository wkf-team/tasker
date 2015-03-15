<?php

/**
 * This is the model class for table "user_has_project".
 *
 * The followings are the available columns in table 'user_has_project':
 * @property integer $user_id
 * @property integer $project_id
 * @property integer $get_notifications
 *
 * The followings are the available model relations:
 * @property User $user
 */
class UserHasProject extends CActiveRecord
{
	public static function HasUserAccess($project_id, $user_id)
	{
		$right = new UserHasProject();
		$right->user_id = $user_id;
		$right->project_id = $project_id;
		return $right->search()->itemCount; // 0 - no access, 1 - has access
	}
	
	public static function SetUserAccess($project_id, $user_id, $notification)
	{
		$right = new UserHasProject();
		$right->user_id = $user_id;
		$right->project_id = $project_id;
		$right->get_notifications = $notification;
		return $right->save();
	}
	
	public static function SwitchUserAccess($project_id, $user_id)
	{
		$right = new UserHasProject();
		$right->user_id = $user_id;
		$right->project_id = $project_id;
		$right = $right->findByPk($right->getPrimaryKey());
		if ($right)
		{
			$right->get_notifications = 1 - $right->get_notifications;
			return $right->save();
		}
		return false;
	}
	
	public static function RemoveUserAccess($project_id, $user_id)
	{
		$admin_id = 1;
		if ($user_id == $admin_id) return false;
		$right = new UserHasProject();
		$right->isNewRecord = false;
		$right->user_id = $user_id;
		$right->project_id = $project_id;
		$right->delete();
		return true;
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_has_project';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, project_id, get_notifications', 'required'),
			array('user_id, project_id, get_notifications', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, project_id, get_notifications', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'Пользователь',
			'project_id' => 'Проект',
			'get_notifications' => 'Оповещения',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('get_notifications',$this->get_notifications);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserHasProject the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
