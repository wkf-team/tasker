<?php
class SendmailCommand extends CConsoleCommand
{
	public function run()
	{
		// unresolved tickets
		$ticketsOverdue = Ticket::model()->findAll(array(
			'condition' => 'status_id < 6 AND due_date < SUBDATE(NOW(), 1)',
			'order' => 'owner_user_id'
		));
		if (count($ticketsOverdue) > 0) Sendmail::mailOverdueTickets($ticketsOverdue);
		// weekly report
		if (date("N") == "1") {
			$ticketsClosed = Ticket::model()->findAll(array(
				'condition' => 'status_id >= 6 AND resolution_id = 2 AND end_date > subdate(now(), 7)',
				'order' => 'owner_user_id'
			));
			Sendmail::mailWeeklyStatus($ticketsClosed, $ticketsOverdue);
		}
		// every 3 days digest
		if (((int)date("j")) % 3 == 0) {
			$ticketsDigest = Ticket::model()->findAll(array(
				'condition' => 'status_id < 6',
				'order' => 'owner_user_id'
			));
			if (count($ticketsDigest) > 0) Sendmail::mailDigestTickets($ticketsDigest);
		}
	}
}
?>