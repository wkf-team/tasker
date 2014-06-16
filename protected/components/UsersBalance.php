<?php
class UsersBalance extends CWidget {
 
    public $users;
 
    public function run() {
		$this->users = VUsersBalance::model()->findAll();
        $this->render('usersBalance');
    }
 
}
?>