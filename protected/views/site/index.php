<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Добро пожаловать в систему <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<?php
$this->widget('MyTasks'); 
?>