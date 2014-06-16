<?php
class TermsBreak extends CWidget {
 
    public $breaks;
 
    public function run() {
		$this->breaks = VTermsBreak::model()->findAll();
        $this->render('termsBreak');
    }
 
}
?>