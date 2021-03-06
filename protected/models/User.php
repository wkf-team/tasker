<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $name
 * @property integer $is_active
 * @property string $mail
 * @property string $password
 * @property integer $work_time_per_week
 * @property integer $usergroup_id
 * @property integer $notification_enabled
 * @property integer $digest_enabled
 *
 * The followings are the available model relations:
 * @property Attachment[] $attachments
 * @property Comment[] $comments
 * @property SpentTime[] $spentTimes
 * @property Ticket[] $tickets
 * @property Ticket[] $tickets1
 * @property Ticket[] $tickets2
 * @property Ticket[] $tickets3
 * @property TicketHistory[] $ticketHistories
 * @property TicketHistory[] $ticketHistories1
 * @property TicketHistory[] $ticketHistories2
 * @property TicketHistory[] $ticketHistories3
 * @property TicketHistory[] $ticketHistories4
 * @property Usergroup $usergroup
 * @property Project[] $projects
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function CheckLevel($level)
	{
		if ($level == 0) return true;
		if (Yii::app()->user->isGuest) return false;
		return User::model()->findByPk(Yii::app()->user->id)->usergroup->level >= $level;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, usergroup_id, notification_enabled, digest_enabled', 'required'),
			array('work_time_per_week, usergroup_id, notification_enabled, digest_enabled, is_active', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('mail', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, mail, work_time_per_week, usergroup_id, notification_enabled, digest_enabled', 'safe', 'on'=>'search'),
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
			'attachments' => array(self::HAS_MANY, 'Attachment', 'author_id'),
			'comments' => array(self::HAS_MANY, 'Comment', 'author_id'),
			'spentTimes' => array(self::HAS_MANY, 'SpentTime', 'user_id'),
			'tickets' => array(self::HAS_MANY, 'Ticket', 'author_user_id'),
			'tickets1' => array(self::HAS_MANY, 'Ticket', 'owner_user_id'),
			'tickets2' => array(self::HAS_MANY, 'Ticket', 'tester_user_id'),
			'tickets3' => array(self::HAS_MANY, 'Ticket', 'responsible_user_id'),
			'ticketHistories' => array(self::HAS_MANY, 'TicketHistory', 'author_user_id'),
			'ticketHistories1' => array(self::HAS_MANY, 'TicketHistory', 'owner_user_id'),
			'ticketHistories2' => array(self::HAS_MANY, 'TicketHistory', 'tester_user_id'),
			'ticketHistories3' => array(self::HAS_MANY, 'TicketHistory', 'responsible_user_id'),
			'ticketHistories4' => array(self::HAS_MANY, 'TicketHistory', 'hist_create_user_id'),
			'usergroup' => array(self::BELONGS_TO, 'Usergroup', 'usergroup_id'),
			'projects' => array(self::MANY_MANY, 'Project', 'user_has_project(user_id, project_id)'),
			'projectSettings' => array(self::HAS_MANY, 'UserHasProject', 'user_id'),
		);
	}
	
	public function getProjectSettings($project)
	{
		foreach ($this->projectSettings as $p) if ($p->project_id == $project->id) return $p;
		return null;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Имя',
			'is_active' => 'Активен',
			'mail' => 'Почта',
			'password' => 'Пароль',
			'work_time_per_week' => 'Рабочих часов в неделю',
			'usergroup_id' => 'Группа',
			'notification_enabled' => 'Оповещения',
			'digest_enabled' => 'Дайджест',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('mail',$this->mail,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('work_time_per_week',$this->work_time_per_week);
		$criteria->compare('usergroup_id',$this->usergroup_id);
		$criteria->compare('notification_enabled',$this->notification_enabled);
		$criteria->compare('digest_enabled',$this->digest_enabled);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}