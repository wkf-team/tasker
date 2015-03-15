<?php
class SendmailCommand extends CConsoleCommand
{
	public function run()
	{
		// unresolved tickets
		Sendmail::mailOverdueTickets();
		// weekly report
		if (date("N") == "1") Sendmail::mailWeeklyStatus();
		// every 3 days digest
		if (((int)date("j")) % 3 == 0) Sendmail::mailDigestTickets();
	}
}
?>