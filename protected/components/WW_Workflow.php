<?php
class WW_Workflow extends CWidget {
 
    public $model;
 
    public function run() {
        $this->render('WWUI_workflow', array('model'=>$this->model));
    }
 
}
?>