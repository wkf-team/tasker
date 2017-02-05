<?php
/****
Step between two states of ticket
Allowed states:
1. Open
2. Reopen
3. Blocked
4. In work
5. On test
6. Done
7. Closed

old vals: 1 - open, 2 - in work, 3 - done, 4 - closed
*/
class WorkflowStep extends CModel
{
	public $state_from;
	public $state_to;
	public $step_name;
	public $button_name;
	public $priority;
	public $input_data;
	
	function __construct($sf, $st, $step, $btn, $p, $in)
	{
		$this->state_from = $sf;
		$this->state_to = $st;
		$this->step_name = $step;
		$this->button_name = $btn;
		$this->priority = $p;
		$this->input_data = $in;
	}
	
	public function attributeNames()
	{
		return array('state_from', 'state_to', 'step_name', 'button_name', 'priority', 'input_data');
	}
	
	public static function IsActionWithResolution($action)
	{
		return $action == "close" || $action == "done" || $action == "test";
	}
	
	public static function GetAction($sf, $step)
	{
		switch ($sf)
		{
			case 1: switch($step)
			{
				case 'start' : return new WorkflowStep(1, 4, 'start', 'start', 10, '');
				case 'close' : return new WorkflowStep(1, 7, 'close', 'close', 0, 'resolution comment');
			}
			break;
			case 2: switch($step)
			{
				case 'start' : return new WorkflowStep(2, 4, 'start', 'start', 10, '');
				case 'withdraw' : return new WorkflowStep(2, 1, 'withdraw', 'withdraw', 5, 'comment');
				case 'close' : return new WorkflowStep(2, 7, 'close', 'close', 0, 'resolution comment');
			}
			break;
			case 3: switch($step)
			{
				case 'start' : return new WorkflowStep(3, 4, 'start', 'start', 10, '');
				case 'withdraw' : return new WorkflowStep(3, 1, 'withdraw', 'withdraw', 5, 'comment');
				case 'close' : return new WorkflowStep(3, 7, 'close', 'close', 0, 'resolution comment');
			}
			break;
			case 4: switch($step)
			{
				case 'test' : return new WorkflowStep(4, 5, 'test', 'test', 10, 'resolution comment');
				case 'done' : return new WorkflowStep(4, 6, 'done', 'done', 10, 'resolution comment');
				case 'blocked' : return new WorkflowStep(4, 3, 'blocked', 'blocked', 8, '');
				case 'close' : return new WorkflowStep(4, 7, 'close', 'close', 0, 'resolution comment');
			}
			break;
			case 5: switch($step)
			{
				case 'pass' : return new WorkflowStep(5, 6, 'pass', 'pass', 10, '');
				case 'fail' : return new WorkflowStep(5, 2, 'fail', 'fail', 8, 'comment');
				case 'close' : return new WorkflowStep(5, 7, 'close', 'close', 0, 'resolution comment');
			}
			break;
			case 6: switch($step)
			{
				case 'reopen' : return new WorkflowStep(6, 2, 'reopen', 'reopen', 10, 'comment');
				case 'close' : return new WorkflowStep(6, 7, 'close', 'close', 0, '');
			}
			break;
			case 7: switch($step)
			{
				case 'reopen' : return new WorkflowStep(7, 2, 'reopen', 'reopen', 10, 'comment');
			}
			break;
		}
	}
	
	public static function GetListOfActions($ticket)
	{
		$steps;
		switch($ticket->status_id) {
			case 1: $steps = array(WorkflowStep::GetAction(1, 'start'), WorkflowStep::GetAction(1, 'close')); break;
			case 2: $steps = array(WorkflowStep::GetAction(2, 'start'), WorkflowStep::GetAction(2, 'withdraw'),
									WorkflowStep::GetAction(2, 'close')); break;
			case 3: $steps = array(WorkflowStep::GetAction(3, 'start'), WorkflowStep::GetAction(3, 'withdraw'),
									WorkflowStep::GetAction(3, 'close')); break;
			case 4: $steps = array(WorkflowStep::GetAction(4, 'test'), WorkflowStep::GetAction(4, 'done'), 
									WorkflowStep::GetAction(4, 'blocked'),	WorkflowStep::GetAction(4, 'close')); break;
			case 5:	$steps = array(WorkflowStep::GetAction(5, 'pass'), WorkflowStep::GetAction(5, 'fail'),
									WorkflowStep::GetAction(5, 'close')); break;
			case 6: $steps = array(WorkflowStep::GetAction(6, 'reopen'), WorkflowStep::GetAction(6, 'close')); break;
			case 7: $steps = array(WorkflowStep::GetAction(7, 'reopen')); break;
		}
		$ret_steps = array();
		foreach ($steps as $step) if ($step->IsAllowed($ticket)) $ret_steps[] = $step;
		return $ret_steps;
	}
	
	/**
		Is allowed for current user and specified ticket
	*/
	public function IsAllowed($ticket)
	{
		if($this->state_from == 4 && $this->state_to == 5) return $ticket->tester_user_id != null;
		if($this->state_from == 4 && $this->state_to == 6) return $ticket->tester_user_id == null;
		return true;
	}
	
	public function PerformStep($ticket)
	{
		if ($ticket->status_id == $this->state_from && $this->IsAllowed($ticket))
		{
			$ticket->status_id = $this->state_to;
			switch ($this->step_name) {
				case 'start' :
				//$ticket->owner_user_id = Yii::app()->user->id;
				break;
				case 'test' :
				$ticket->owner_user_id = $ticket->tester_user_id;
				break;
				case 'pass' :
				$ticket->end_date = date("Y-m-d");
				break;
				case 'fail' :
				$ticket->owner_user_id = $ticket->responsible_user_id;
				$ticket->end_date = null;
				$ticket->resolution_id = null;
				break;
				case 'done' : 
				$ticket->end_date = date("Y-m-d");
				break;
				case 'reopen' : 
				$ticket->end_date = null;
				$ticket->resolution_id = null;
				break;
				case 'close' : 
				if(!$ticket->end_date) $ticket->end_date = date("Y-m-d");
				break;
			}
			$ticket->save();
		}
	}
}

?>