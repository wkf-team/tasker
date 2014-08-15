<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . " - Отчеты";
$this->breadcrumbs=array(
	'Отчеты',
);
?>

<h1>Отчеты</h1>

<?php
	$this->widget('RW_GoalsComplete'); 
	$this->widget('RW_UsersBalance'); 
	$this->widget('RW_TermsBreak'); 
?>