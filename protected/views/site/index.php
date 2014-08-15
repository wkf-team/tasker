<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Добро пожаловать в систему <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<h2>Мои задачи</h2>
<div id="myTasks">
<?php
$this->widget('TW_MyTasks'); 
?>
</div>