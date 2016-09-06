<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Мои задачи</h1>
<div id="myTasks">
<?php
$this->widget('TW_MyTasks'); 
?>
</div>