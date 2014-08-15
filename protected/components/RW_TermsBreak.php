<?php
class RW_TermsBreak extends CWidget {
 
    public $breaks;
 
    public function run() {
		$this->breaks = VTermsBreak::model()->findAll();
        $this->render('RWUI_termsBreak');
    }
 
}
?>