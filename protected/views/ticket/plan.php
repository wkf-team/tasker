<?php
/* @var $this TicketController */
/* @var $model Ticket */

$this->breadcrumbs=array(
	'Бэклог',
);
?>
<h1>Бэклог</h1>
<?php
$this->widget('TW_TicketHList', [
	'filterForBacklog'=>true,
	'showFooterButtons'=>true,
	'iteration_id'=>$iteration_id,
]);
?>