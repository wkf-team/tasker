<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . " - Отчеты";
$this->breadcrumbs=array(
	'Отчеты',
);
?>

<h1>Отчеты</h1>

<?php
	$this->widget('GoalsComplete'); 
	$this->widget('UsersBalance'); 
	$this->widget('TermsBreak'); 
?>