<?php
class RW_UsersBalance extends CWidget {
 
    public $users;
 
    public function run() {
		$this->users = VUsersBalance::model()->findAll();
        $this->render('RWUI_usersBalance');
    }
 
}
?>