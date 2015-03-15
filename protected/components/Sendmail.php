<?php
class Sendmail //extends CComponent
{
	public static $mailPrefix = "WKF.Task ";
	public static $baseUrl = "http://wkf.bit.ru/wkf.task/";
	
	private static function normalizeUrl($arr)
	{
		return Sendmail::$baseUrl.strstr(CHtml::normalizeUrl($arr),'?');
	}
	
	// Еженедельный дайджест на всех по статусу
	public static function mailWeeklyStatus()
	{
		$ticketsClosed = Ticket::model()->findAll(array(
			'condition' => 'status_id >= 6 AND resolution_id = 2 AND end_date > subdate(now(), 7)',
			'order' => 'owner_user_id'
		));
		if (count($ticketsClosed) > 0) {
			$message = "За прошедшую неделю были выполнены задачи:<ul>";
			for ($i = 0; $i < count($ticketsClosed); $i++) {
				$message .= "<li>".CHtml::link($ticketsClosed[$i]->subject, Sendmail::$baseUrl.strstr(CHtml::normalizeUrl(array('ticket/view', 'id'=>$ticketsClosed[$i]->id)),'?'))."</li>";
			}
			$message .= "</ul><br/>";
		} else {
			$message = "За прошедшую неделю ни одной задачи закрыто не было.<br/>";
		}
		$ticketsOverdue = Ticket::model()->findAll(array(
			'condition' => 'status_id < 6 AND due_date < SUBDATE(NOW(), 1)',
			'order' => 'owner_user_id'
		));
		if (count($ticketsOverdue) > 0) {
			$message .= "В данный момент просрочены задачи:<ul>";
			for ($i = 0; $i < count($ticketsOverdue); $i++) {
				$message .= "<li>".CHtml::link($ticketsOverdue[$i]->subject, Sendmail::$baseUrl.strstr(CHtml::normalizeUrl(array('ticket/view', 'id'=>$ticketsOverdue[$i]->id)),'?'))." (".$ticketsOverdue[$i]->ownerUser->name.")</li>";
			}
			$message .= "</ul><br/>";
		} else {
			$message .= "Просроченных задач нет.<br/>";
		}
		Sendmail::mail(User::model()->findAll(array(
			'join'=>'LEFT OUTER JOIN usergroup ON t.usergroup_id = usergroup.id',
			'condition'=>'usergroup.level >= 10'
		)), "Статус ".date("d.m.Y"), $message);
	}
	
	// Просрочка задачи
	public static function mailOverdueTickets()
	{
		$tickets = Ticket::model()->findAll(array(
			'condition' => 'status_id < 6 AND due_date < SUBDATE(NOW(), 1)',
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
			'condition' => 'status_id < 6',
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
	public static function mailChangeTicket($ticket)
	{
		Sendmail::mail(array($ticket->ownerUser, $ticket->authorUser), "Изменено: ".$ticket->subject, User::model()->findByPk(Yii::app()->user->id)->name." изменил данные по задаче: ".CHtml::link($ticket->subject, Yii::app()->createAbsoluteUrl('ticket/view', array('id'=>$ticket->id))));
	}
	
	// Изменение срока по задаче
	public static function mailChangeTicketDate($ticket)
	{
		Sendmail::mail(array($ticket->ownerUser, $ticket->authorUser), "Изменен срок: ".$ticket->subject, User::model()->findByPk(Yii::app()->user->id)->name." изменил срок по задаче: ".CHtml::link($ticket->subject, Yii::app()->createAbsoluteUrl('ticket/view', array('id'=>$ticket->id))));
	}
	
	// Создание/назначение задачи
	public static function mailAssignTicket($ticket)
	{
		Sendmail::mail(array($ticket->ownerUser), "Новая задача: ".$ticket->subject, "Вам назначена новая задача: ".CHtml::link($ticket->subject, Yii::app()->createAbsoluteUrl('ticket/view', array('id'=>$ticket->id))));
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