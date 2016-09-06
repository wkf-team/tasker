<?php

/**
 * This is the model class for table "project".
 *
 * The followings are the available columns in table 'project':
 * @property integer $id
 * @property string $name
 * @property string $start_date
 * @property integer $is_active
 * @property string $current_version
 * @property string $next_version
 *
 * The followings are the available model relations:
 * @property Iteration[] $iterations
 * @property Ticket[] $tickets
 * @property TicketHistory[] $ticketHistories
 * @property User[] $users
 */
class Project extends CActiveRecord
{
	public static function GetSelected()
	{
		$selected = Project::model()->find(array(
			'condition'=>'is_selected=1 AND u.user_id=:uid',
			'join'=>'INNER JOIN user_has_project AS u ON u.project_id = t.id',
			'params'=>array(':uid'=>Yii::app()->user->id),
		));
		if ($selected) return $selected;
		$user = User::model()->findByPk(Yii::app()->user->id);
		if ($user && count($user->projects) > 0) {
			$user->projects[0]->SetSelected();
			return $user->projects[0];
		}
		return null;
	}
	
	public function SetDefaultRights()
	{
		$admin_id = 1;
		UserHasProject::SetUserAccess($this->id, $admin_id, 0);
		if (Yii::app()->user->id != $admin_id)
			UserHasProject::SetUserAccess($this->id, Yii::app()->user->id, 1);
		$this->SetSelected();
	}
	
	public function SetSelected()
	{
		Yii::app()->db->createCommand("UPDATE user_has_project SET is_selected=IF(project_id=:pid,1,0) WHERE user_id=:uid")->execute(array(':pid'=>$this->id, ':uid'=>Yii::app()->user->id));
	}
	
	public function IsSelected()
	{
		$right = UserHasProject::model()->findByAttributes(array(
			'user_id'=>Yii::app()->user->id,
			'project_id'=>$this->id,
		));
		return $right->is_selected;
	}
	
	public function encodeDate($date)
	{
		if ($date == null || $date == "") return $date;
		$dt = new DateTime($date);
		return CHtml::encode($dt->format("d.m.Y"));
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'project';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, start_date, is_active', 'required'),
			array('is_active', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('current_version, next_version', 'length', 'max'=>25),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, start_date, is_active, current_version, next_version', 'safe', 'on'=>'search'),
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
			'iterations' => array(self::HAS_MANY, 'Iteration', 'project_id'),
			'tickets' => array(self::HAS_MANY, 'Ticket', 'project_id'),
			'ticketHistories' => array(self::HAS_MANY, 'TicketHistory', 'project_id'),
			'users' => array(self::MANY_MANY, 'User', 'user_has_project(project_id, user_id)'),
			'userSettings' => array(self::HAS_MANY, 'UserHasProject', 'project_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Имя',
			'start_date' => 'Дата начала',
			'is_active' => 'Активен',
			'current_version' => 'Текущая версия',
			'next_version' => 'Следующая версия',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('current_version',$this->current_version,true);
		$criteria->compare('next_version',$this->next_version,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Project the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
