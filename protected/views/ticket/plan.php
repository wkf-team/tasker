<?php
/* @var $this TicketController */
/* @var $model Ticket */

$this->breadcrumbs=array(
	'Задачи'=>array('index'),
	'Структура работ',
);
?>
<h1>Структура работ</h1>
<? $this->widget('TW_TicketHList', ['dataProvider'=>$dataProvider]) ?>
<button id="btnAddEpic" class="btnAddEpic">New Epic</button>
<button id="btnAddStory">New Single UserStory</button>