<?php
class Sendmail extends CController
{
	public static $mailPrefix = "WKF.Task ";
	public static $baseUrl = "http://wkfteam.ru/wkf.task/";
	
	private static function normalizeUrl($arr)
	{
		return Sendmail::$baseUrl.strstr(CHtml::normalizeUrl($arr),'?');
	}
	
	// Еженедельный дайджест на всех по статусу
	public static function mailWeeklyStatus()
	{
		$projects = Project::model()->findAll(array('condition' => 'is_active = 1'));
		$instance = new Sendmail('sendmail');
		foreach ($projects as $project)
		{
			$ticketsClosed = Ticket::model()->findAll(array(
				'condition' => 'status_id >= 6 AND resolution_id = 2 AND end_date > subdate(now(), 7) AND project_id=:pid',
				'params' => array(':pid'=>$project->id),
				'order' => 'owner_user_id'
			));
			$ticketsOverdue = Ticket::model()->findAll(array(
				'condition' => 'status_id < 6 AND due_date < SUBDATE(NOW(), 1) AND project_id=:pid',
				'params' => array(':pid'=>$project->id),
				'order' => 'owner_user_id'
			));
			if (count($ticketsClosed) > 0 || count($ticketsOverdue) > 0) {
				$message = $instance->renderInternal("views/SL_projectStatus.php", array('ticketsClosed'=>$ticketsClosed,'ticketsOverdue'=>$ticketsOverdue,'projectName'=>$project->name), true);
				$result = Sendmail::mail(User::model()->findAll(array(
					'join'=>'LEFT OUTER JOIN usergroup AS ug ON t.usergroup_id = ug.id
								LEFT OUTER JOIN user_has_project AS up ON t.id = up.user_id',
					'condition'=>'ug.level >= 10 AND t.notification_enabled = 1 AND up.project_id = :pid AND up.get_notifications = 1',
					'params'=>array(':pid'=>$project->id)
				)), "Статус ".date("d.m.Y")." по проекту ".$project->name, $message);
			}
		}
	}
	
	// Просрочка задачи
	public static function mailOverdueTickets()
	{
		$tickets = Ticket::model()->findAll(array(
			'join' => 'INNER JOIN project ON project.id = t.project_id
						INNER JOIN user_has_project AS up ON up.project_id = t.project_id AND up.user_id = t.owner_user_id',
			'condition' => 'status_id < 6 AND due_date < SUBDATE(NOW(), 1) AND project.is_active = 1 AND up.get_notifications = 1',
			'order' => 'owner_user_id'
		));
		if (count($tickets) > 0)
		{
			$user = $tickets[0]->ownerUser;
			$message = "У вас просрочены следующие задачи:<ul>";
			for ($i = 0; $i < count($tickets); $i++)
			{
				if ($tickets[$i]->owner_user_id != $user->id) {
					Sendmail::mail(array($user), "Просрочены задачи", $message."</ul>");
					$user = $tickets[$i]->ownerUser;
					$message = "У вас просрочены следующие задачи:<ul>";
				}
				$message .= "<li>".CHtml::link($tickets[$i]->subject, Sendmail::normalizeUrl(array('ticket/view', 'id'=>$tickets[$i]->id))).". ".CHtml::link("Отложить на 3 дня", Sendmail::normalizeUrl(array('ticket/postpone', 'id'=>$tickets[$i]->id)))."</li>";
			}
			Sendmail::mail(array($user), "Просрочены задачи", $message."</ul>");
		}
	}
	
	// Дайджест задач
	public static function mailDigestTickets()
	{
		$tickets = Ticket::model()->findAll(array(
			'join' => 'INNER JOIN project ON project.id = t.project_id
						INNER JOIN user_has_project AS up ON up.project_id = t.project_id AND up.user_id = t.owner_user_id',
			'condition' => 'status_id < 6 AND project.is_active = 1 AND up.get_notifications = 1',
			'order' => 'owner_user_id'
		));
		if (count($tickets) > 0)
		{
			$user = $tickets[0]->ownerUser;
			$message = "Вам назначены следующие задачи:<ul>";
			$counter = 0;
			for ($i = 0; $i < count($tickets); $i++)
			{
				if ($tickets[$i]->owner_user_id != $user->id) {
					if ($counter > 0 && $user->digest_enabled) Sendmail::mail(array($user), "Дайджест задач", $message."</ul>");
					$user = $tickets[$i]->ownerUser;
					$counter = 0;
					$message = "Вам назначены следующие задачи:<ul>";
				}
				$counter++;
				$message .= "<li>".CHtml::link($tickets[$i]->subject, Sendmail::normalizeUrl(array('ticket/view', 'id'=>$tickets[$i]->id))).". ".CHtml::link("Отложить на 3 дня", Sendmail::normalizeUrl(array('ticket/postpone', 'id'=>$tickets[$i]->id)))."</li>";
			}
			if ($counter > 0 && $user->digest_enabled) Sendmail::mail(array($user), "Дайджест задач", $message."</ul>");
		}
	}
	
	// Регистрация пользователя
	public static function mailCreateUser($user)
	{
		Sendmail::mail(array($user), "Добро пожаловать!", "Добро пожаловать в систему ".CHtml::link("WKF.Task", Yii::app()->createAbsoluteUrl('site/index'))."<br/>Ваш логин: ".$user->name."<br/>Для получения пароля обратитесь к администратору ресурса.");
	}
	
	// Изменение данных по задаче
	public static function mailChangeTicket($ticket, $comment=null, $addUser = null, $removeUser = null)
	{
		if ($ticket->project->is_active == 1)
		{
			$users = array();
			if ($ticket->ownerUser->getProjectSettings($ticket->project)->get_notifications == 1 && $ticket->ownerUser->id != $removeUser) $users[] = $ticket->ownerUser;
			if ($ticket->authorUser->getProjectSettings($ticket->project)->get_notifications == 1 && $ticket->authorUser->id != $removeUser) $users[] = $ticket->authorUser;
			if ($addUser != null && $addUser != $ticket->ownerUser->id && $addUser != $ticket->authorUser->id
			&& ($user = User::model()->findByPk($addUser)) && $user->getProjectSettings($ticket->project)->get_notifications == 1) $users[] = $user;
			Sendmail::mail($users, "Изменено: ".$ticket->subject, Yii::app()->user->name." изменил данные по задаче ".CHtml::link($ticket->subject, Yii::app()->createAbsoluteUrl('ticket/view', array('id'=>$ticket->id))).($comment == null ? "" : " с комментарием: ".$comment));
		}
	}
	
	// Изменение срока по задаче
	public static function mailChangeTicketDate($ticket)
	{
		if ($ticket->project->is_active == 1)
		{
			$users = array();
			if ($ticket->ownerUser->getProjectSettings($ticket->project)->get_notifications == 1) $users[] = $ticket->ownerUser;
			if ($ticket->authorUser->getProjectSettings($ticket->project)->get_notifications == 1) $users[] = $ticket->authorUser;
			Sendmail::mail($users, "Изменен срок: ".$ticket->subject, Yii::app()->user->name." изменил срок по задаче ".CHtml::link($ticket->subject, Yii::app()->createAbsoluteUrl('ticket/view', array('id'=>$ticket->id))));
		}
	}
	
	// Создание/назначение задачи
	public static function mailAssignTicket($ticket, $comment=null)
	{
		if ($ticket->project->is_active == 1 && $ticket->ownerUser->getProjectSettings($ticket->project)->get_notifications == 1)
		{
			Sendmail::mail(array($ticket->ownerUser), "Новая задача: ".$ticket->subject, "Вам назначена новая задача: ".CHtml::link($ticket->subject, Yii::app()->createAbsoluteUrl('ticket/view', array('id'=>$ticket->id))).($comment == null ? "" : " с комментарием: ".$comment));
		}
	}
	
	// Изменение статуса по задаче
	public static function mailMakeWFTicket($ticket, $comment=null, $addUser = null, $removeUser = null)
	{
		if ($ticket->project->is_active == 1)
		{
			$users = array();
			if ($ticket->ownerUser->getProjectSettings($ticket->project)->get_notifications == 1 && $ticket->ownerUser->id != $removeUser) $users[] = $ticket->ownerUser;
			if ($ticket->authorUser->getProjectSettings($ticket->project)->get_notifications == 1 && $ticket->authorUser->id != $removeUser) $users[] = $ticket->authorUser;
			if ($addUser != null && $addUser != $ticket->ownerUser->id && $addUser != $ticket->authorUser->id
			&& ($user = User::model()->findByPk($addUser)) && $user->getProjectSettings($ticket->project)->get_notifications == 1) $users[] = $user;
			Sendmail::mail($users, "Изменен статус: ".$ticket->subject, Yii::app()->user->name." изменил статус по задаче ".CHtml::link($ticket->subject, Yii::app()->createAbsoluteUrl('ticket/view', array('id'=>$ticket->id))).($comment == null ? "" : " с комментарием: ".$comment));
		}
	}
	
	// Комментарий к задаче
	public static function mailCommentTicket($ticket, $comment)
	{
		if ($ticket->project->is_active == 1)
		{
			$users = array();
			if ($ticket->ownerUser->getProjectSettings($ticket->project)->get_notifications == 1) $users[] = $ticket->ownerUser;
			if ($ticket->authorUser->getProjectSettings($ticket->project)->get_notifications == 1) $users[] = $ticket->authorUser;
			Sendmail::mail($users, "Новый комментарий к задаче: ".$ticket->subject, Yii::app()->user->name." добавил комментарий к задаче ".CHtml::link($ticket->subject, Yii::app()->createAbsoluteUrl('ticket/view', array('id'=>$ticket->id))).": ".$comment);
		}
	}
	
	// base mail send with respect to users
	public static function mail($to_list, $subject, $message, $forced = false)
	{
		$to_str = "";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=windows-1251' . "\r\n";
		foreach ($to_list as $user)
			if ((Yii::app()->name == 'WKF.Task Console' || $forced || $user->id != Yii::app()->user->id) && $user->notification_enabled)
				$to_str .= $user->mail.", ";
		$subj = iconv("utf-8", "windows-1251", Sendmail::$mailPrefix.$subject);
		$text = iconv("utf-8", "windows-1251", $message."<br/>Это письмо было сформировано автоматически. Пожалуйста, не отвечайте на него.");
		if ($to_str)
			return mail ($to_str, $subj, $text, $headers);
		else return false;
	}
}
?>